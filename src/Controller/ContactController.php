<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use App\Service\ApiSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\DataMethod;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api', name: 'api_')]
class ContactController extends AbstractController
{
    public function __construct(
        ContactRepository $contactRepository,
        EntityManagerInterface $entityManager,
        PersistenceManagerRegistry $doctrine,
        DataMethod $dataMethod
    ) {
        $this->contactRepository = $contactRepository;
        $this->entityManager = $entityManager;
        $this->doctrine = $doctrine;
        $this->dataMethod = $dataMethod;
    }

    #[Route('/', name: 'index', methods: 'GET')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Contact Controller',
        ]);
    }

    #[Route('/contacts', name: 'get_all_contacts', methods: 'GET')]
    public function getAllContacts(): JsonResponse
    {
        $contacts = $this->contactRepository->apiFindAll();
        return new JsonResponse($contacts, Response::HTTP_OK);
    }

    #[Route('/contact/{id}', requirements: ['id' => '\d+'], name: 'get_one_contact_by_id', methods: 'GET')]
    public function getOneContactById(int $id): JsonResponse
    {
        $contact = $this->contactRepository->findOneBy(['id' => $id]);

        if ($contact !== null) {
            $data = [
                'id' => $contact->getId(),
                'firstName' => $contact->getFirstname(),
                'lastName' => $contact->getLastname(),
                'email' => $contact->getMail(),
                'adress' => $contact->getAdress(),
                'phone' => $contact->getPhone(),
                'age' => $contact->getAge(),
            ];

            return new JsonResponse($data, Response::HTTP_OK);
        }
        return new JsonResponse("This contact doesn't exist", Response::HTTP_NOT_FOUND);
    }

    #[Route('/contact', name: 'create_new_contact', methods: 'POST')]
    public function createNewContact(
        Request $request,
        ValidatorInterface $validator,
    ): JsonResponse {
        $contact = new Contact();

        $informations = $this->dataMethod->getDataFromRequest($request);
        $dataIsClean = $this->dataMethod->cleanData($informations);
        $contact = $this->dataMethod->hydrate($dataIsClean, $contact);

        $searchDuplicateContact = $this->contactRepository->findOneBy([
            'mail' => $dataIsClean['mail'],
            'firstname' => $dataIsClean['firstname'],
            'lastname' => $dataIsClean['lastname'],
        ]);

        if ($searchDuplicateContact !== null) {
            $data = [
                'id' => $searchDuplicateContact->getId(),
                'firstName' => $searchDuplicateContact->getFirstname(),
                'lastName' => $searchDuplicateContact->getLastname(),
                'email' => $searchDuplicateContact->getMail(),
                'adress' => $searchDuplicateContact->getAdress(),
                'phone' => $searchDuplicateContact->getPhone(),
                'age' => $searchDuplicateContact->getAge(),
            ];
            return new JsonResponse([
                'status' => 'Contact is already created!',
                'contact' => $data
            ], Response::HTTP_NOT_FOUND);
        }
        $errors = $validator->validate($contact);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, 400);
        }
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($contact);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Contact created!'], Response::HTTP_CREATED);
    }

    #[Route('/contact/{id}', requirements: ['id' => '\d+'], name: 'update_contact_by_id', methods: 'PUT')]
    public function editOneContact(
        Request $request,
        int $id
    ): JsonResponse {
        $contact = $this->contactRepository->findOneBy(['id' => $id]);
        if ($contact !== null) {
            $data = $this->dataMethod->getDataFromRequest($request);

            empty($data['firstname']) ? true : $contact->setFirstName($data['firstname']);
            empty($data['lastname']) ? true : $contact->setLastName($data['lastname']);
            empty($data['mail']) ? true : $contact->setMail($data['mail']);
            empty($data['age']) ? true : $contact->setMail($data['age']);
            empty($data['adress']) ? true : $contact->setMail($data['adress']);
            empty($data['phone']) ? true : $contact->setPhone($data['phone']);

            $updatedContact = $this->contactRepository->updateContact($contact);
            return new JsonResponse([
                'Contact Updated' => $updatedContact->toArray()], Response::HTTP_OK);
        }
        return new JsonResponse("This contact doesn't exist", Response::HTTP_NOT_FOUND);
    }

    #[Route('/contact/{id}', requirements: ['id' => '\d+'], name: 'delete_contact_by_id', methods: 'DELETE')]
    public function delete(int $id): JsonResponse
    {
        $contact = $this->contactRepository->findOneBy(['id' => $id]);

        if ($contact !== null) {
            $this->contactRepository->removecontact($contact);
            return new JsonResponse(['status' => 'contact deleted'], Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse("This contact doesn't exist", Response::HTTP_NOT_FOUND);
    }
}
