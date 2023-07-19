<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\ServiceException;
use App\Repository\UserRepository;

class UserService
{
    public function __construct(private readonly UserRepository $userRepository) {}

    /**
     * @throws ServiceException
     */
    public function getTagsByUserId(int $userId): array
    {
        $user = $this->getUserById($userId);
        return $user->getTags();
    }

    /**
     * @throws ServiceException
     */
    public function getUserById(int $userId): User
    {
        /**
         * @var User $user
         */
        $user = $this->userRepository->findOneBy(['id' => $userId]);
        if (!$user) {
            throw new ServiceException('User not found');
        }
        return $user;
    }

    /**
     * @throws ServiceException
     */
    public function getUserByNickname(string $nickname): User
    {
        /**
         * @var User $user
         */
        $user = $this->userRepository->findOneBy(['nickname' => $nickname]);
        if (!$user) {
            throw new ServiceException('User not found');
        }
        return $user;
    }
}
