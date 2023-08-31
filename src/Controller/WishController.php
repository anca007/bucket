<?php

namespace App\Controller;

use App\Repository\WishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/wish', name: 'wish_')]
class WishController extends AbstractController
{

    //injection de dépendance posible dans le constructeur également
    public function __construct(private WishRepository $wishRepository)
    {
    }

    #[Route('/list', name: 'list')]
    public function list(): Response
    {
        $wishes = $this->wishRepository->findBy(["isPublished" => true], ["dateCreated" => "DESC"]);

        return $this->render('wish/list.html.twig', [
            "wishes" => $wishes
        ]);
    }

    #[Route('/detail/{id}', name: 'detail', requirements: ['id' => '[0-9]+'])]
    public function detail(int $id): Response
    {
        $wish = $this->wishRepository->find($id);

        if (!$wish) {
            throw $this->createNotFoundException("Oops ! Wish not found !");
        }

        return $this->render('wish/detail.html.twig', [
            "wish" => $wish
        ]);
    }
}
