<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/wish', name: 'wish_')]
class WishController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(): Response
    {
        //TODO renvoyer la liste des wish
        return $this->render('wish/list.html.twig');
    }

    #[Route('/detail/{id}', name: 'detail', requirements: ['id' => '[0-9]+'])]
    public function detail(int $id): Response
    {
        //TODO renvoyer un wish
        return $this->render('wish/detail.html.twig');
    }
}
