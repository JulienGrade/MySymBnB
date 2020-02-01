<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher et de gérer le formulaire de connexion
     *
     * @Route("/login", name="account_login")
     * @param AuthenticationUtils $utils
     * @return \Symfony\Component\HttpFoundation\Response
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register()
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        return $this->render('account/registration.html.twig', [
           'form'=> $form->createView()
        ]);
    }
}
