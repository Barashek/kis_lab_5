<?php


namespace App\Controller;


use App\Entity\Company;
use App\Entity\Vacancy;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class VacancyController extends AbstractController implements TokenAuthenticatedController
{

    public function create(Request $request)
    {
        $reqBody = json_decode($request->getContent(), true);
        if ($this->isValid($reqBody)) {
            $entityManager = $this->getDoctrine()->getManager();
            $vacancy = new Vacancy();
            $vacancy = $this->setData($reqBody, $vacancy);
            $entityManager->persist($vacancy);
            $entityManager->flush();
            return new Response($vacancy->getId(), 200);
        }

        return new Response('Invalid Data', 422);
    }

    public function update($id, Request $request)
    {
        $reqBody = json_decode($request->getContent(), true);

        if ($this->isValid($reqBody)) {
            $entityManager = $this->getDoctrine()->getManager();
            $repository = $this->getDoctrine()->getRepository(Vacancy::class);
            $vacancy = $repository->find($id);
            $vacancy = $this->setData($reqBody, $vacancy);
            $entityManager->persist($vacancy);
            $entityManager->flush();

            $serializer = SerializerBuilder::create()->build();
            $jsonContent = $serializer->serialize($vacancy, 'json');
            return JsonResponse::fromJsonString($jsonContent);
        }
        return new Response('Invalid Data', 422);
    }

    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Vacancy::class);
        $vacancy = $repository->find($id);
        if (!$vacancy) {
            return new Response('Not found', 404);
        }
        $entityManager->remove($vacancy);
        $entityManager->flush();
        return new Response('OK', 200);
    }


    private function isValid($data): bool
    {
        if (
            is_string($data['title']) && !empty($data['title']) &&
            is_string($data['description']) && !empty($data['description']) &&
            (is_float($data['minSalary']) || is_int($data['minSalary'])) &&
            (is_float($data['maxSalary']) || is_int($data['maxSalary'])) &&
            is_int($data['minExperience']) &&
            is_int($data['maxExperience']) &&
            is_int($data['companyId'])
        ) {
            return true;
        }
        return false;
    }

    private function setData(array $data, Vacancy $vacancy): Vacancy
    {
        $vacancy->setTitle($data['title']);
        $vacancy->setDescription($data['description']);
        $vacancy->setMinSalary($data['minSalary']);
        $vacancy->setMaxSalary($data['maxSalary']);
        $vacancy->setMinExperience($data['minExperience']);
        $vacancy->setMaxExperience($data['maxExperience']);

        $repository = $this->getDoctrine()->getRepository(Company::class);
        $company = $repository->find($data['companyId']);
        $vacancy->setCompany($company);
        return $vacancy;
    }
}