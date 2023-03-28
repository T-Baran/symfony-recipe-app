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

    public function createDTO($id): UserDTO
    {
        $DTO = new UserDTO();
        if(!is_null($id)){
            $record = $this->userRepository->find($id);
            $this->setRecord($record);
            $DTO->transferFrom($record);
        }
        return $DTO;
    }

    public function saveRecord($userDTO): User
    {
        if (is_null($this->getRecord())) {
            $user = $this->createUser($userDTO);
        } else {
            $user = $this->getRecord();
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
        if(!$plainPassword = $userDTO->getPlainPassword()){
            throw new \Exception('When creating new account must provide password');
        }
        $user->setPassword($this->encoder->hashPassword($user, $plainPassword));
        $this->userRepository->save($user);
        return $user;
    }

    public function setCurrentUser(object $object): void
    {
        if (method_exists($object, 'getUser') && $object->getUser() === null && method_exists($object, 'setUser')) {
            $object->setUser($this->security->getUser());
        }
    }

    public function setRecord($record):void
    {
        if ($record instanceof User) {
            $this->record = $record;
        } else {
            throw new \InvalidArgumentException('Invalid type for record, should provide instance of User');
        }
    }
//
//    private function isEmailUnique(string $email): void
//    {
//        $user = $this->userRepository->findOneBy(['email' => $email]);
//        if ($user !== null) {
//            throw new EmailNotUniqueException(sprintf('The email "%s" is already in use.', $email));
//        }
//    }
}
