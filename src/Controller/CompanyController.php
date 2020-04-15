<?php


namespace App\Controller;

use App\Entity\Company;
use App\Controller\TokenAuthenticatedController;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class CompanyController extends AbstractController implements TokenAuthenticatedController
{

    public function create(Request $request)
    {
        $reqBody = json_decode($request->getContent(), true);
        if ($this->isValid($reqBody)) {
            $entityManager = $this->getDoctrine()->getManager();
            $company = new Company();
            $company = $this->setData($reqBody, $company);
            $entityManager->persist($company);
            $entityManager->flush();
            return new JsonResponse($company->getId());
        }
        return new Response('Invalid data', 422);
    }

    public function update($id, Request $request)
    {
        $reqBody = json_decode($request->getContent(), true);
        if ($this->isValid($reqBody)) {
            $entityManager = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(Company::class);
            $company = $repository->find($id);
            $company = $this->setData($reqBody, $company);
            $entityManager->persist($company);
            $entityManager->flush();

            $serializer = SerializerBuilder::create()->build();
            $jsonContent = $serializer->serialize($company, 'json');
            return JsonResponse::fromJsonString($jsonContent);
        }
        return new Response('Invalid data', 422);
    }

    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Company::class);
        $company = $repository->find($id);
        if (!$company) {
            return new Response('Not found', 404);
        }
        $entityManager->remove($company);
        $entityManager->flush();
        return new Response('OK', 200);
    }


    private function isValid($data): bool
    {
        if (
            is_string($data['title']) && !empty($data['title']) &&
            is_string($data['description']) && !empty($data['description'])
        ) {
            return true;
        }
        return false;
    }

    private function setData(array $data, Company $company): Company
    {
        $company->setTitle($data['title']);
        $company->setDescription($data['description']);
        return $company;
    }
}