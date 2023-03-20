<?php

namespace App\Factory;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

final class UserFactory extends ModelFactory
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->passwordHasher = $passwordHasher;
    }

    protected function getDefaults(): array
    {
        return [
            'email' => self::faker()->email(),
            'plainPassword' => 'test',
            'roles' => [],
            'username' => self::faker()->userName(),
        ];
    }

    protected function initialize(): self
    {
        return $this
            ->afterInstantiate(function (User $user): void {
                if ($user->getPlainPassword()) {
                    $user->setPassword(
                        $this->passwordHasher->hashPassword($user, $user->getPlainPassword()));
                }
            });
    }

    protected static function getClass(): string
    {
        return User::class;
    }
}
