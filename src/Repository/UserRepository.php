<?php

namespace App\Repository;

use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;

final class UserRepository implements UserRepositoryInterface
{
	private const ENTITY = User::class;

	/**
	 * @var EntityManagerInterface
	 */
	private $entityManager;

	/**
	 * @var ObjectRepository
	 */
	private $objectRepository;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
		$this->objectRepository = $this->entityManager->getRepository(self::ENTITY);
	}

	/**
	 * @param int $id
	 * @return User
	 */
	public function find(int $id): ?User
	{
		$this->entityManager->find(self::ENTITY, $id);
	}

	/**
	 * @param string $username
	 * @return User
	 */
	public function findOneByEmail(string $username): ?User
	{
		return $this->objectRepository->findOneBy(['email' => $username]);
	}

	/**
	 * @param User $user
	 */
	public function store(User $user)
	{
		$this->entityManager->persist($user);
		$this->entityManager->flush();
	}

	/**
	 * @param User $user
	 */
	public function update(User $user)
	{
		$this->entityManager->persist($user);
		$this->entityManager->flush();
	}

	/**
	 * @param User $user
	 */
	public function delete(User $user)
	{
		$this->entityManager->remove($user);
		$this->entityManager->flush();
	}
}
