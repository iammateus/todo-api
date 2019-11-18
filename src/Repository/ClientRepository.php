<?php

namespace App\Repository;

use App\Entity\Client\Client;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ClientRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;

final class ClientRepository implements ClientRepositoryInterface
{

	private const ENTITY = Client::class;

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
	 * Finds an active client by its id
	 * @param string $id
	 * @return Client|null
	 */
	public function findActive(string $id): ?Client
	{
		return $this->objectRepository->findOneBy(["id" => $id, "active" => true]);
	}

	/**
	 * @param Client $client
	 * @return void
	 */
	public function store(Client $client)
	{
		$this->entityManager->persist($client);
		$this->entityManager->flush();
	}

	/**
	 * @param Client $client
	 * @return void
	 */
	public function update(Client $client)
	{
		$this->entityManager->persist($client);
		$this->entityManager->flush();
	}

	/**
	 * @param Client $client
	 * @return void
	 */
	public function delete(Client $client)
	{
		$this->entityManager->remove($client);
		$this->entityManager->flush();
	}
}
