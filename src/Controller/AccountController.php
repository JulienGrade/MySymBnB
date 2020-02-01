<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\PasswordUpdateType;
use App\Form\RegistrationType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher et de gérer le formulaire de connexion
     *
     * @Route("/login", name="account_login")
     * @param AuthenticationUtils $utils
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        // Permet de ne pas avoir a retaper son nom d'utilisateur
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Permet de se déconnecter
     *
     * @Route("/logout", name="account_logout")
     *
     */
    public function logout()
    {

    }

    /**
     * Permet d'afficher le formulaire d'inscritpion
     *
     * @Route("/register", name="account_register")
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre compte a bien été créé ! Vous pouvez maintenant vous connecter !"
            );
            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig', [
           'form'=> $form->createView()
        ]);
    }

    /**
     * Permet d'afficher et traiter le formulaire de modification de profil
     *
     * @Route("/account/profile", name="account_profile")
     *
     * @param Request $request
     * @return Response
     */
    public function profile(Request $request, ObjectManager $manager)
    {
        // Ici on récupère l'utilisateur connecté
        $user = $this->getUser();

        // On crée le formulaire en précisant le type ici account
        $form = $this->createForm(AccountType::class, $user);

        // On demande au formulaire de gérer la requete
        $form->handleRequest($request);

        // Ici on enregistre en bdd apres conditions
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications de votre profil ont bien été enregistrées !"
            );
        }

        return $this->render('account/profile.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier le mot de passe
     *
     * @Route("/account/password-update", name="account_password")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param ObjectManager $manager
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, ObjectManager $manager)
    {
        $passwordUpdate = new PasswordUpdate();

        // On récupère l'utilisateur connecté
        $user = $this->getUser();

        // On crée le formulaire
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        // On demande au formulaire de gérer la requete
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // Verifie que le oldPassword est identique au password de l'user ici $user
            if(!password_verify($passwordUpdate->getOldPassword(), $user->getHash())){
                // Ici on gère l'erreur
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapez n'est pas votre mot de passe actuel !"));
            } else {
                // Ici on s'occupe d'enregistrer le nouveau mot de passe car conforme
                $newPassword = $passwordUpdate->getNewPassword();
                // Ici on encode
                $hash = $encoder->encodePassword($user, $newPassword);

                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été modifié !"
                );

                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher le profil de l'utilisateur
     *
     * @Route("/account", name="account_index")
     *
     * @return Response
     */
    public function myAccount(){
        return $this->render('user/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }
}
