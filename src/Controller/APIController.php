<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\APISerializer;

#[Route('/api', name: 'api_')]
class APIController extends AbstractController
{
    #[Route('/', name: 'index', methods: 'GET')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/APIController.php',
        ]);
    }

    #[Route('/contacts', name: 'all_contacts', methods: 'GET')]
    public function getAllContacts(ContactRepository $contactRepository, APISerializer $APISerializer): Response
    {
        $contacts = $contactRepository->findAll();

        $data = $APISerializer->toJSON($contacts);

        return $APISerializer->response($data);

    }

}
