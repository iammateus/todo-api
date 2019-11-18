<?php

namespace App\Repository;

use App\Entity\AccessToken\AccessToken;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectRepository;

final class  AccessTokenRepository implements AccessTokenRepositoryInterface
{

	private const ENTITY = AccessToken::class;

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
	 * @param string $id
	 * @return AccessToken|null
	 */
	public function find($id): ?AccessToken
	{
		return $this->objectRepository->find($id);
	}

	/**
	 * @param AccessToken $client
	 * @return void
	 */
	public function save(AccessToken $accessToken): void
	{
		$this->entityManager->persist($accessToken);
		$this->entityManager->flush();
	}
}
