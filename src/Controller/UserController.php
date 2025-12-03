<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController

{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {

        $randomNumber = rand(1, 100);

        return $this->render('user/index.html.twig', [
             'lucky_number' => $randomNumber,
        ]);

    }
}
