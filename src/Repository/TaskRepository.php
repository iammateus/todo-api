<?php

namespace App\Repository;

use App\Entity\Task\Task;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\QueryBuilder;

final class TaskRepository implements TaskRepositoryInterface
{
	/**
     * @var EntityManagerInterface
     */
    private $entityManager;
	
	/**
     * @var ObjectRepository
     */
	private $objectRepository;
	
	/**
     * @var QueryBuilder
     */
	private $queryBuilder;
	
	/**
     * @param EntityManagerInterface $entityManager
     * @return void
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
		$this->entityManager = $entityManager;
    	$this->objectRepository = $this->entityManager->getRepository(Task::class);
    	$this->queryBuilder = $this->entityManager->createQueryBuilder();
	}
	
	/**
     * @param int $taskId
     * @return Task|null
     */
	public function find(int $taskId)
    {
        $task = $this->objectRepository->find($taskId);
        return $task;
	}
	
	/**
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
	public function findAll(int $limit = null,int $offset = null): array
	{
		if($limit === null || $offset === null){
			return $this->objectRepository->findAll();
		}

		$query = $this->queryBuilder
					  ->select("t")
					  ->from("App\Entity\Task\Task", "t")
					  ->setFirstResult($offset)
					  ->setMaxResults($limit)
					  ->orderBy("t.id", "desc")
					  ->getQuery();

		return $query->getResult();

	}
	
	/**
     * @param string $title
     * @return array
     */
	public function findAllByTitle(string $title): array
	{
		return [];
	}

	/**
     * @param Task $task
     */
	public function store(Task $task)
    {
		$this->entityManager->persist($task);
		$this->entityManager->flush();
	}

	/**
     * @param Task $task
     */
	public function update(Task $task)
    {
		$this->entityManager->persist($task);
		$this->entityManager->flush();	
    }
	
	/**
     * @param Task $task
     */
	public function delete(Task $task)
    {
        $this->entityManager->remove($task);
		$this->entityManager->flush();
    }

}
