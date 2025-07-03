<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AdminPanelController extends AbstractController
{
    #[Route('/', name: 'panel')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/AdminPanelController.php',
        ]);
    }
}
