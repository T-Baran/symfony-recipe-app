<?php

namespace App\Service;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager
{
    private string $username;

    public function __construct(private Security $security, private UserPasswordHasherInterface $encoder, private UserRepository $userRepository)
    {
    }

    public function setCurrentUser(Object $object):void
    {
        if(method_exists($object, 'getUser') && $object->getUser() === null && method_exists($object, 'setUser')) {
            $object->setUser($this->security->getUser());
        }
    }

    public function handleUser(UserDTO $userDTO, ?User $user = null):void
    {
        if(!isset($user)){
            $user = $this->createNewAndPersist();
        }
        $userDTO->transferTo($user);
        if($plainPassword = $userDTO->getPlainPassword()){
            $user->setPassword($this->encoder->hashPassword($user, $plainPassword));
        }
//        if(!is_null($plainPassword = $userDTO->getPlainPassword())){
//        }
        $this->setUsername($user->getUsername());
        $this->userRepository->onlyFlush();
    }

    public function createNewAndPersist():User
    {
        $user = new User();
        $this->userRepository->save($user);
        return $user;
    }

    function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }
}
