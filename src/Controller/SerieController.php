<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/series')]
class SerieController extends AbstractController
{
    #[Route('', name: 'serie_list', methods: ['GET'], )]
    public function list(): Response
    {
        return $this->render('serie/list.html.twig');
    }

    #[Route('/add', name: 'serie_add', methods: ['GET', 'POST'], )]
    public function add(): Response {
        return $this->render('serie/add.html.twig');
    }

    #[Route('/details/{id}', name: 'serie_detail', requirements: ['id'=>'\d+'], methods: ['GET', 'POST'])]
    public function detail(int $id): Response
    {

        dump($id);
        return $this->render('serie/detail.html.twig');
    }
}
