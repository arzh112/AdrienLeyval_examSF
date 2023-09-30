<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Services\UsersWhithoutAdmin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function home(Security $security, UserRepository $userRepository): Response
    {
        $connectedUser = $security->getUser();
        
        $users = UsersWhithoutAdmin::arrayUsers($userRepository);

        return $this->render('home/home.html.twig', [
            'connectedUser' => $connectedUser,
            'users' => $users
        ]);
    }
}
