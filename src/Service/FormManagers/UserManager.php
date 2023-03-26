<?php

namespace App\Service\FormManagers;

use App\DTO\UserDTO;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager extends AbstractFormManager
{
    public function __construct(private Security $security, private UserPasswordHasherInterface $encoder, private UserRepository $userRepository)
    {
    }

    public const FORM_TYPE = UserType::class;

    public function createDTO(): UserDTO
    {
        return new UserDTO();
    }

    public function saveRecord($userDTO, $id = null): User
    {
        if (is_null($id)) {
            $user = $this->createUser($userDTO);
        } else {
            $user = $this->userRepository->find($id);
            $userDTO->transferTo($user);
            if($plainPassword = $userDTO->getPlainPassword()){
                $user->setPassword($this->encoder->hashPassword($user, $plainPassword));
            }
        }
        return $user;
    }

    public function flushRecord($user): void
    {
        $this->userRepository->onlyFlush();
        $this->setId($user->getId());
    }

    public function getLocation(): string
    {
        return '/api/users/' . $this->getId();
    }

    private function createUser(UserDTO $userDTO): User
    {
        $user = new User();
        $userDTO->transferTo($user);
        if($plainPassword = $userDTO->getPlainPassword()){
            $user->setPassword($this->encoder->hashPassword($user, $plainPassword));
        }
        $this->userRepository->save($user);
        return $user;
    }

    public function setCurrentUser(object $object): void
    {
        if (method_exists($object, 'getUser') && $object->getUser() === null && method_exists($object, 'setUser')) {
            $object->setUser($this->security->getUser());
        }
    }
}
