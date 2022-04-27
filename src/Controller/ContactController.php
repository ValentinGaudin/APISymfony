<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\APISerializer;
use App\Service\DataMethod;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

#[Route('/api', name: 'api_')]
class ContactController extends AbstractController
{
    public function __construct(
        ContactRepository $contactRepository,
        APISerializer $APISerializer
    ) {
        $this->contactRepository = $contactRepository;
        $this->APISerializer = $APISerializer;
    }

    #[Route('/', name: 'index', methods: 'GET')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'Contact Controller',
        ]);
    }

    #[Route('/contacts', name: 'get_all_contacts', methods: 'GET')]
    public function getAllContacts(): Response
    {
        $contacts = $this->contactRepository->apiFindAll();
        $data = $this->APISerializer->toJSON($contacts);

        return $this->APISerializer->response($data);
    }

    #[Route('/contact/{id}', requirements: ['id' => '\d+'], name: 'get_one_contact_by_id', methods: 'GET')]
    public function getOneContact(int $id)
    {
        $contact = $this->contactRepository->find($id);
        $data = $this->APISerializer->toJSON($contact);

        return $this->APISerializer->response($data);
    }

    #[Route('/contact', name: 'create_new_contact', methods: 'POST')]
    public function createNewContact(
        Request $request,
        DataMethod $dataMethod,
        EntityManagerInterface $entityManager,
        PersistenceManagerRegistry $doctrine,
            ): Response {

        $contact = New Contact();
        
        $informations = $dataMethod->getData($request);
        $dataIsClean = $dataMethod->cleanData($informations);

        $contact->setFirstname($dataIsClean['firstname']);
        $contact->setLastname($dataIsClean['lastname']);
        $contact->setAdress($dataIsClean['adress']);
        $contact->setPhone($dataIsClean['phone']);
        $contact->setMail($dataIsClean['mail']);
        $contact->setAge($dataIsClean['age']);

        $entityManager = $doctrine->getManager();
        $entityManager->persist($contact);
        $entityManager->flush();

        return new Response('Your new contact has been added', 201);
    }

}
