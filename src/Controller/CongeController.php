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
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->isGranted('ROLE_ADMIN')) {
            $conges = $congeRepository->findAll();
        } else {
            $conges = $congeRepository->findBy(['demandeur' => $this->getUser()]);
        }

        return $this->render('conge/index.html.twig', [
            'conges' => $conges,
        ]);
    }

    #[Route('/new', name: 'app_conge_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $conge = new Conge();
        $conge->setDemandeur($user);
        $conge->setDateDemande(new \DateTime());
        $conge->setDecision('En attente');

        // Formulaire standard pour l'employé (is_admin_processing reste false par défaut)
        $form = $this->createForm(CongeType::class, $conge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($conge);
            $entityManager->flush();

            $this->addFlash('success', 'Votre demande de congé a bien été enregistrée.');

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
        // Sécurité : Seul un administrateur peut traiter/modifier un congé existant
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // On active le mode d'administration sur le formulaire
        $form = $this->createForm(CongeType::class, $conge, [
            'is_admin_processing' => true
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // Si le statut change et n'est plus "En attente"
            if ($conge->getDecision() !== 'En attente') {
                $conge->setDateReponse(new \DateTime()); // Définit la date de réponse à aujourd'hui
                $conge->setValideur($this->getUser());   // Enregistre l'administrateur connecté
            }

            $entityManager->flush();

            $this->addFlash('success', 'La décision sur la demande de congé a été enregistrée avec succès.');

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
            $this->addFlash('success', 'La demande a été supprimée.');
        }

        return $this->redirectToRoute('app_conge_index', [], Response::HTTP_SEE_OTHER);
    }
}