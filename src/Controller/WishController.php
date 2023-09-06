<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use App\Utils\Censurator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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

    #[Route('/add', name: 'add')]
    #[IsGranted("ROLE_USER")]
    public function add(
        Request $request,
        EntityManagerInterface $entityManager,
        Censurator $censurator

    ): Response
    {
        $wish = new Wish();
        $wishForm = $this->createForm(WishType::class, $wish);

        $wishForm->handleRequest($request);

        if($wishForm->isSubmitted() && $wishForm->isValid()){

            //$wish->setDateCreated(new \DateTime());
            $wish->setDescription($censurator->purify($wish->getDescription()));
            $wish->setUser($this->getUser());
            $entityManager->persist($wish);
            $entityManager->flush();

            $this->addFlash("success", "Idea successfully added !");
            return $this->redirectToRoute("wish_detail", ['id' => $wish->getId()]);
        }

        return $this->render('wish/add.html.twig', [
            "wishForm" => $wishForm
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    #[IsGranted("ROLE_USER")]
    public function edit(int $id,
                         WishRepository $wishRepository,
                         EntityManagerInterface $entityManager,
                         Request $request): Response
    {

        $wish = $wishRepository->find($id);

        if($wish->getUser() != $this->getUser()){
            throw $this->createAccessDeniedException("You can't edit this idea !");
        }

        $wishForm = $this->createForm(WishType::class, $wish);

        $wishForm->handleRequest($request);

        if($wishForm->isSubmitted() && $wishForm->isValid()){

            $wish->setDateUpdated(new \DateTime());
            $entityManager->persist($wish);
            $entityManager->flush();

            $this->addFlash("success", "Idea successfully updated !");
            return $this->redirectToRoute("wish_detail", ['id' => $wish->getId()]);
        }


        return $this->render('wish/edit.html.twig', [
            "wishForm" => $wishForm
        ]);
    }



    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '[0-9]+'])]
    #[IsGranted("ROLE_USER")]
    public function delete(int $id, WishRepository $wishRepository, EntityManagerInterface $entityManager): Response
    {
        $wish =$wishRepository->find($id);
        $entityManager->remove($wish);
        $entityManager->flush();

        $this->addFlash('success', "Le souhait a été supprimé !");

        return $this->redirectToRoute("wish_list");
    }


}
