<?php

namespace App\Security\Provider;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    /**
     * @var UserLoaderInterface
     */
    private UserLoaderInterface $userLoader;

    /**
     * @param UserLoaderInterface $userLoader
     */
    public function __construct(UserLoaderInterface $userLoader)
    {
        $this->userLoader = $userLoader;
    }


    /**
     * @inheritDoc
     */
    public function loadUserByUsername($username): ?UserInterface
    {
        return $this->userLoader->loadUserByUsername($username);
    }

    /**
     * @inheritDoc
     */
    public function refreshUser(UserInterface $user)
    {
        // TODO: Implement refreshUser() method.
    }

    /**
     * @inheritDoc
     */
    public function supportsClass($class)
    {
        return $class === User::class;
    }
}