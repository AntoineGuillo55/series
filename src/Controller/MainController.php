<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/home', name: 'main_home')]
    #[Route('/')]
    public function home(): Response
    {

        $username = "Antoine";
        $serie = ["name" => "Emily in Paris", "year" => 2020];

        return $this->render('main/home.html.twig', [
            'controller_name' => 'MainController',
            "serie" => $serie,
            "name" => $username
        ]);
    }

    #[Route('/test', name: 'main_test')]
    public function test(): Response
    {

        $data = file_get_contents('https://swapi.dev/api/people/1');
        dump($data);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, "https://swapi.dev/api/people/1");
        curl_setopt($curl, CURLOPT_POSTFIELDS, ['key' => 'val']);
        curl_exec($curl);
        curl_close($curl);

        return $this->render('main/test.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

}
