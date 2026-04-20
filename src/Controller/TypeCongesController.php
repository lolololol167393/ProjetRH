<?php

namespace App\Controller;

use App\Entity\TypeConges;
use App\Form\TypeCongesType;
use App\Repository\TypeCongesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/type/conges')]
final class TypeCongesController extends AbstractController
{
    #[Route(name: 'app_type_conges_index', methods: ['GET'])]
    public function index(TypeCongesRepository $typeCongesRepository): Response
    {
        return $this->render('type_conges/index.html.twig', [
            'type_conges' => $typeCongesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_type_conges_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typeConge = new TypeConges();
        $form = $this->createForm(TypeCongesType::class, $typeConge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeConge);
            $entityManager->flush();

            return $this->redirectToRoute('app_type_conges_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_conges/new.html.twig', [
            'type_conge' => $typeConge,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_conges_show', methods: ['GET'])]
    public function show(TypeConges $typeConge): Response
    {
        return $this->render('type_conges/show.html.twig', [
            'type_conge' => $typeConge,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_type_conges_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeConges $typeConge, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypeCongesType::class, $typeConge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_type_conges_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_conges/edit.html.twig', [
            'type_conge' => $typeConge,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_conges_delete', methods: ['POST'])]
    public function delete(Request $request, TypeConges $typeConge, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeConge->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($typeConge);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_type_conges_index', [], Response::HTTP_SEE_OTHER);
    }
}
