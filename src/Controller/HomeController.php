<?php

// src/Controller/HomeController.php

namespace App\Controller;

use App\Repository\CongeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CongeRepository $congeRepository): Response
    {
        // 1. Récupérer l'utilisateur connecté
        $user = $this->getUser();
        $conges = [];

        // 2. Si un utilisateur est connecté, on récupère ses congés
        if ($user) {
            if ($this->isGranted('ROLE_ADMIN')) {
                // L'administrateur voit tout
                $conges = $congeRepository->findAll();
            } else {
                // L'utilisateur classique ne voit que les siens
                $conges = $congeRepository->findBy(['demandeur' => $user]);
            }
        }

        // 3. Transmettre la variable 'conges' au template Home
        return $this->render('home/index.html.twig', [
            'conges' => $conges,
        ]);
    }
}
