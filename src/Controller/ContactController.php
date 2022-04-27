<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\APISerializer;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/api', name: 'api_')]
class ContactController extends AbstractController
{
    public function __construct(
        ContactRepository $contactRepository,
        APISerializer $APISerializer
    ){
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

    #[Route('/contacts', name: 'all_contacts', methods: 'GET')]
    public function getAllContacts(): Response
    {
        $contacts = $this->contactRepository->apiFindAll();
        $data = $this->APISerializer->toJSON($contacts);

        return $this->APISerializer->response($data);
    }

    #[Route('/contact/{id}', requirements: ['id' => '\d+'], name:'get_one_by_id', methods: 'GET')]
    public function getOneContact(int $id)
    {
        $contact = $this->contactRepository->find($id);
        $data = $this->APISerializer->toJSON($contact);

        return $this->APISerializer->response($data);
    }

    #[Route('/contact', name:'create_new', methods: 'POST')]
    public function createNewContact()
    {
        
    }
}
