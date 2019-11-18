<?php

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\RefreshToken\RefreshToken;
use Doctrine\Common\Persistence\ObjectRepository;
use App\Repository\RefreshTokenRepositoryInterface;

final class  RefreshTokenRepository implements RefreshTokenRepositoryInterface
{

	private const ENTITY = RefreshToken::class;

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
	 * Finds a refresh token by its id
	 * @param string $id
	 * @return RefreshToken|null
	 */
	public function find(string $id): ?RefreshToken
	{
		return $this->objectRepository->find($id);
	}

	/**
	 * @param RefreshToken $client
	 * @return void
	 */
	public function save(RefreshToken $refreshToken): void
	{
		$this->entityManager->persist($refreshToken);
		$this->entityManager->flush();
	}
}
