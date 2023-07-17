<?php

namespace App\Factory;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory
{
    public function __construct(private readonly UserPasswordHasherInterface  $passwordHasher)
    {
    }

    public function create(string $email, string $nickname, string $plainPassword, string $role = User::DEFAULT_ROLE): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setNickname($nickname);
        $user->setRoles([$role]);

        $user->setPlainPassword($plainPassword);
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPlainPassword()));

        // sets created/updated at fields
        $dateCreate = \DateTimeImmutable::createFromFormat('U', time());
        $user->setCreatedAt($dateCreate);
        $user->setUpdatedAt($dateCreate);

        $user->setIsDeleted(false);

        return $user;
    }
}
