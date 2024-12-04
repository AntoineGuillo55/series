<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/series', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('/{page}', name: 'list', requirements: ['page' => '\d+'], methods: ['GET'])]
    public function list(SerieRepository $serieRepository, int $page = 1): Response
    {

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

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    #[Route('/edit/{id}', name: 'serie_edit', methods: ['GET', 'POST'])]
    #[IsGranted("ROLE_USER")]
    public function save(
        Request                $request,
        EntityManagerInterface $entityManager,
        SerieRepository        $serieRepository,
        FileUploader $fileUploader,
        int $id = null
    ): Response
    {
        $serie = !$id ? new Serie() : $serieRepository->find($id);

        if (!$serie) {
            throw $this->createNotFoundException('No such serie !');
        }

        $serieForm = $this->createForm(SerieType::class, $serie);

        $serieForm->get('genres')->setData(explode(' / ', $serie->getGenres()));
        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {

            $backdrop = $serieForm->get('backdrop')->getData();

            if($backdrop) {

                $fileName = $fileUploader->upload($backdrop, $this->getParameter('backdrop_path'), $serie->getName());
                $serie->setBackdrop($fileName);
            }

            $serie->setGenres(implode(' / ',$serieForm->get('genres')->getData()));
            $entityManager->persist($serie);
            $entityManager->flush();

            $this->addFlash('success', "The Tv Show " . $serie->getName() . " has been updated");
            return $this->redirectToRoute('serie_detail', ['id' => $serie->getId()]);
        }

        return $this->render('serie/save.html.twig', [
            'serieForm' => $serieForm,
            'serieId' => $id
        ]);

    }

    #[Route('/details/{id}', name: 'detail', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
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

    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '\d+'])]
    public function delete(Serie $serie, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($serie);
        $entityManager->flush();

        $this->addFlash('success', 'Serie has been removed !');

        return $this->redirectToRoute('serie_list');
    }
}
