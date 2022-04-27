<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function getAllContacts(ContactRepository $contactRepository): Response
    {
        $contacts = $contactRepository->findAll();

        return $this->json([
            'message' => 'Get all contacts from database',
            'contacts' => $contacts
        ]);
    }
}
