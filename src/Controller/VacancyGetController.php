<?php


namespace App\Controller;

use App\Entity\Vacancy;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class VacancyGetController extends AbstractController
{
    public function show($id)
    {
        $repository = $this->getDoctrine()->getRepository(Vacancy::class);
        $vacancy = $repository->find($id);
        if (!$vacancy) {
            return new Response("Not Found", 404);
        }

        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($vacancy, 'json');
        return JsonResponse::fromJsonString($jsonContent);
    }
}
