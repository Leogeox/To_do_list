<?php
namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

final class TaskController extends AbstractController
{
    #[Route('/task', name: 'app_task')]
    public function index(TaskRepository $taskRepository, EntityManagerInterface $entityManager,Request $request): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $task->setUserId($user);
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirect($request->getUri());
        }
        $tasks = $taskRepository->findBy(['user_id' => $user], ['id' => 'ASC']);

        return $this->render('task/index.html.twig', [
            'form' => $form->createView(),
            'tasks' => $tasks
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
