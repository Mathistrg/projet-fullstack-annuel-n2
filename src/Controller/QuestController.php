<?php

namespace App\Controller;

use App\Entity\Quest;
use App\Form\Quest1Type;
use App\Repository\QuestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/quest')]
class QuestController extends AbstractController
{
    #[Route('/', name: 'app_quest_index', methods: ['GET'])]
    public function index(QuestRepository $questRepository): Response
    {
        return $this->render('quest/index.html.twig', [
            'quests' => $questRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_quest_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $quest = new Quest();
        $form = $this->createForm(Quest1Type::class, $quest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($quest);
            $entityManager->flush();

            return $this->redirectToRoute('app_quest_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quest/new.html.twig', [
            'quest' => $quest,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_quest_show', methods: ['GET'])]
    public function show(Quest $quest): Response
    {
        $comments = $quest->getComments();
        return $this->render('quest/show.html.twig', [
            'quest' => $quest,
            'comments' => $comments
        ]);
    }

    #[Route('/{id}/edit', name: 'app_quest_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quest $quest, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Quest1Type::class, $quest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_quest_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quest/edit.html.twig', [
            'quest' => $quest,
            'form' => $form,
        ]);
    }

}