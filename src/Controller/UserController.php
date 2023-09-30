<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\UsersWhithoutAdmin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user_list', methods: ['GET'])]
    public function list(UserRepository $userRepository): Response
    {
        $users = UsersWhithoutAdmin::arrayUsers($userRepository);

        return $this->render('user/list.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/{id}', name: 'app_user_id', methods: ['GET'])]
    public function user(User $user): Response
    {
        return $this->render('user/user.html.twig', [
            'user' => $user,
        ]);
    }
}
