<?php

namespace App\Controller;

use App\Entity\Conge;
use App\Form\CongeType;
use App\Repository\CongeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/conge')]
final class CongeController extends AbstractController
{
    #[Route(name: 'app_conge_index', methods: ['GET'])]
    public function index(CongeRepository $congeRepository): Response
    {
        // 1. On récupère l'utilisateur connecté
        $user = $this->getUser();
        // Sécurité au cas où aucun utilisateur n'est connecté
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // 2. On filtre pour ne récupérer QUE les congés du demandeur connecté
        $mesConges = $congeRepository->findBy(['demandeur' => $user]);

        // 3. On passe la variable '$mesConges' à la vue sous le nom 'conges'
        return $this->render('conge/index.html.twig', [
            'conges' => $mesConges,
        ]);
    }

    #[Route('/new', name: 'app_conge_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
            if (!$user) {
                return $this->redirectToRoute('app_login'); // Sécurité si non connecté
            }

            $conge = new Conge();

            // 2. Assigner les valeurs par défaut selon l'utilisateur
            $conge->setDemandeur($user);                  // L'utilisateur connecté devient le demandeur
            $conge->setDateDemande(new \DateTime());      // Date du jour automatique
            $conge->setDecision('En attente');            // Statut initial par défaut

            // 3. Créer le formulaire avec l'objet déjà pré-rempli
            $form = $this->createForm(CongeType::class, $conge);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($conge);
                $entityManager->flush();

                return $this->redirectToRoute('app_conge_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('conge/new.html.twig', [
                'conge' => $conge,
                'form' => $form,
            ]);
    }

    #[Route('/{id}', name: 'app_conge_show', methods: ['GET'])]
    public function show(Conge $conge): Response
    {
        return $this->render('conge/show.html.twig', [
            'conge' => $conge,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_conge_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Conge $conge, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CongeType::class, $conge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_conge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('conge/edit.html.twig', [
            'conge' => $conge,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_conge_delete', methods: ['POST'])]
    public function delete(Request $request, Conge $conge, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$conge->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($conge);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_conge_index', [], Response::HTTP_SEE_OTHER);
    }
}
