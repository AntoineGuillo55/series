<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function add(EntityManagerInterface $entityManager): Response
    {

        $serie = new Serie();
        $serie->setBackdrop("backdrop.png")
            ->setDateCreated(new \DateTime())
            ->setName("The gentlemen")
            ->setGenres("Gangsters")
            ->setVote(8)
            ->setFirstAirDate(new \DateTime('-1 year'))
            ->setOverview("Drogue, baston, riches, sexe")
            ->setPopularity(450)
            ->setPoster('poster.png')
            ->setStatus("returning")
            ->setTmdbId(1238431654);

        $entityManager->persist($serie);
        $entityManager->flush();


        return $this->render('serie/add.html.twig');
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
