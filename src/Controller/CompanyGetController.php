<?php


namespace App\Controller;

use App\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\SerializerBuilder;


class CompanyGetController extends AbstractController
{
    public function show($id)
    {
        $repository = $this->getDoctrine()->getRepository(Company::class);
        $company = $repository->find($id);
        if (!$company) {
            return new Response("Not Found", 404);
        }

        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($company, 'json');
        return JsonResponse::fromJsonString($jsonContent);
    }

    public function getVacancies($id)
    {
        $repository = $this->getDoctrine()->getRepository(Company::class);
        $company = $repository->find($id);
        $vacancies = $company->getVacancies();

        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($vacancies, 'json');
        return JsonResponse::fromJsonString($jsonContent);
    }

    public function getCompanies()
    {
        $repository = $this->getDoctrine()->getRepository(Company::class);
        $companies = $repository->findAll();

        $serializer = SerializerBuilder::create()->build();
        $jsonContent = $serializer->serialize($companies, 'json');
        return JsonResponse::fromJsonString($jsonContent);
    }
}