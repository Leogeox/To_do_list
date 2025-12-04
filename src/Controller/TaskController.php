<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

final class TaskController extends AbstractController
{
    #[Route('/task', name: 'app_task')]
    public function index(TaskRepository $taskRepository, UserRepository $userRepository): Response
    {
        $user = $userRepository->find(1); //teste avec mon compte faudra le remplacer quand y aura le systeme de connexion
        $tasks = $taskRepository->findBy(['user_id' => $user], ['id' => 'ASC']);

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }
    


    #[Route('/task/{id}/status', name: 'task_changement_status')]
    public function toggleStatus(Task $task, EntityManagerInterface $entityManager): RedirectResponse
    {
        $StatusActuel = $task->getStatus();

        $NouveauxStatus = match ($StatusActuel) {
            'non' => 'progress',
            'progress' => 'oui',
            'oui' => 'non',
        };

        $task->setStatus($NouveauxStatus);
        $entityManager->flush();

        return $this->redirectToRoute('app_task');
    }


    #[Route('/task/{id}/delete', name: 'task_delete')]
        public function delete(Task $task, EntityManagerInterface $entityManager): RedirectResponse
        {
            $entityManager->remove($task);
            $entityManager->flush();
            return $this->redirectToRoute('app_task');
        }
}

    public function index(Request $request, EntityManagerInterface $em, TaskRepository $repo): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($task);
            $em->flush();

            return $this->redirect($request->getUri());
        }

        $task = $repo->findAll();

        return $this->render('task/index.html.twig', [
            'form' => $form->createView(),
            'task' => $task
        ]);
    }
}
