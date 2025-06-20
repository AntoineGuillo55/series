<?php

namespace App\Controller\Api;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route("/api/serie", name: 'api_serie_')]
class SerieController extends AbstractController
{

    public function __construct(private SerieRepository $serieRepository){

    }
    #[Route('', name: 'all', methods: ['GET'])]
    public function all(): JsonResponse
    {
        $series = $this->serieRepository->findAll();
        return $this->json($series, Response::HTTP_OK, [], ['groups' => 'serie-api']);
    }

    #[Route('/{id}', name: 'one', methods: ['GET'])]
    public function one(Serie $serie): JsonResponse
    {
        return $this->json($serie, Response::HTTP_OK, [], ['groups' => 'serie-api']);
    }

    #[Route('', name: 'add', methods: ['POST'])]
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        $data = $request->getContent();
        $serie = $serializer->deserialize($data, Serie::class, 'json');

//
//        $test = $validator->validate($serie);
//        dd($test);

        $entityManager->persist($serie);
        $entityManager->flush();

        return $this->json($serie, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(Serie $serie, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager): JsonResponse
    {

        $data = $request->getContent();

        $serializer->deserialize($data, Serie::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $serie]);
        $entityManager->persist($serie);
        $entityManager->flush();

        return $this->json($serie, Response::HTTP_ACCEPTED, [], ['groups' => 'serie-api']);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Serie $serie, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($serie);
        $entityManager->flush();

        return $this->json(["delete" => "success"], Response::HTTP_OK);
    }
}
