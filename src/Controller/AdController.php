<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     * @param AdRepository $repo
     * @param SessionInterface $session
     * @return Response
     */
    public function index(AdRepository $repo,SessionInterface $session)
    {


        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }

    /**
     * Permet de créer une annonce
     *
     * @Route("/ads/new", name="ads_create")
     * @return Response
     */
    public function create()
    {

        $ad = new Ad();
        $form = $this->createForm(AdType::class, $ad);


        return$this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher une seule annonce
     *
     * @param $slug
     * @param Ad $ad
     * @return Response
     * @Route("/ads/{slug}", name="ads_show")
     */
    public function show(Ad $ad)
    {

        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }


}
