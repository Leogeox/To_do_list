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
    public function index(TaskRepository $taskRepository, EntityManagerInterface $entityManager,Request $request, UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        // Mode preview : utiliser un utilisateur démo si non connecté
        if (!$user) {
            $user = $userRepository->findOneBy(['email' => 'demo@preview.com']);
            
            // Créer l'utilisateur démo s'il n'existe pas
            if (!$user) {
                $user = new \App\Entity\User();
                $user->setEmail('demo@preview.com');
                $user->setPassword('demo'); // Mot de passe fictif pour preview
                $entityManager->persist($user);
                $entityManager->flush();
            }
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
            'tasks' => $tasks,
            'preview_mode' => !$this->getUser()
        ]);
    }



    #[Route('/task/{id}/status', name: 'task_changement_status')]
    public function toggleStatus(Task $task, EntityManagerInterface $entityManager): RedirectResponse
    {
        $StatusActuel = $task->getStatus();
        
        if ($StatusActuel == 'non'){
            $NouveauxStatus = 'progress';
        }
        elseif ($StatusActuel == 'progress'){
            $NouveauxStatus = 'oui';
        }
        else{
            $NouveauxStatus = 'non';
        }
        
        
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
