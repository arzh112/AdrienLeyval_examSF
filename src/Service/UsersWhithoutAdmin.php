<?php

namespace App\Service;

use App\Repository\UserRepository;

/**
 * Service permettant de renvoyer un tableau de tous les utilisateurs sans les utilisateurs admin
 */
class UsersWhithoutAdmin
{
    public static function arrayUsers(UserRepository $userRepository): array {
        $allUsers = $userRepository->findAll();
        $users = [];
        foreach($allUsers as $user) {
            if($user->getRoles() !== ["ROLE_ADMIN", "ROLE_USER"]) {
                $users[] = $user;
            }
        }
        return $users;
    }
}