<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/series')]
class SerieController extends AbstractController
{
    #[Route('/{page}', name: 'serie_list', requirements: ['page' => '\d+'], methods: ['GET'])]
    public function list(SerieRepository $serieRepository, int $page = 1): Response
    {

//        $series = $serieRepository->findAll();
//        $series = $serieRepository->findByGenresAndPopularity("comedy");
        $maxPage = ceil($serieRepository->count([]) / 50);

        if($page < 1) {
            return $this->redirectToRoute('serie_list', ['page' => 1]);
        }

        if($page > $maxPage) {
            return $this->redirectToRoute('serie_list', ['page' => $maxPage]);
        }

        $series = $serieRepository->findAllWithPagination($page);

        return $this->render('serie/list.html.twig', [
            'series' => $series,
            'currentPage' => $page,
            'maxPage' => $maxPage,
        ]);
    }

    #[Route('/add', name: 'serie_add', methods: ['GET', 'POST'],)]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {

       $serie = new Serie();

       $serieForm = $this->createForm(SerieType::class, $serie);

       $serieForm->handleRequest($request);

       if($serieForm->isSubmitted() && $serieForm->isValid()) {

//           $serie->setDateCreated(new \DateTime());
           $entityManager->persist($serie);
           $entityManager->flush();

           $this->addFlash('success', 'The TV show ' . $serie->getName() . ' has been created');
           return $this->redirectToRoute('serie_detail', ['id' => $serie->getId()]);
       }

        return $this->render('serie/add.html.twig', [
            'serieForm' => $serieForm,
        ]);
    }

    #[Route('/details/{id}', name: 'serie_detail', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function detail(Serie $serie, SerieRepository $serieRepository): Response
    {

//        $serie = $serieRepository->find($id);
//
//        if(!$serie) {
//            throw $this->createNotFoundException("Oops ! Serie not found !");
//        }

        return $this->render('serie/detail.html.twig', [
            "serie" => $serie,
        ]);
    }
}
