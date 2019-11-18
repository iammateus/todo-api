<?php

namespace App\Application\Provider;

use App\Entity\User\User;
use App\Repository\UserRepositoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

final class UserProvider implements UserProviderInterface
{
	/**
	 * @var UserRepositoryInterface
	 */
	private $userRepository;

	/**
	 * UserProvider constructor.
	 * @param UserRepositoryInterface $userRepository
	 */
	public function __construct(UserRepositoryInterface $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	/**
	 * @param string $username
	 * @return UserInterface
	 */
	public function loadUserByUsername($username): UserInterface
	{
		return $this->findUsername($username);
	}

	/**
	 * @param string $username
	 * @return User
	 */
	private function findUsername(string $username): User
	{
		$user = $this->userRepository->findOneByEmail($username);
		if ($user !== null) {
			return $user;
		}

		throw new UsernameNotFoundException(
			sprintf('Username "%s" does not exist.', $username)
		);
	}

	/**
	 * @param UserInterface $user
	 * @return UserInterface
	 */
	public function refreshUser(UserInterface $user): UserInterface
	{
		if (!$user instanceof User) {
			throw new UnsupportedUserException(
				sprintf('Instances of "%s" are not supported.', \get_class($user))
			);
		}

		$username = $user->getUsername();
		return $this->findUsername($username);
	}

	/**
	 * @param $class
	 * @return bool
	 */
	public function supportsClass($class): bool
	{
		return User::class === $class;
	}
}
