<?php

namespace App\Service;

use DateTime;
use App\Entity\Task\Task;
use App\Entity\Task\TaskDTO;
use Psr\Log\LoggerInterface;
use App\Repository\TaskRepository;

final class TaskService
{
	/**
     * @var TaskRepository
     */
	private $taskRepository;

	/**
     * @var LoggerInterface
     */
	private $logger;
	
	/**
     * @param TaskRepository $taskRepository
     * @param LoggerInterface $logger
     * @return void
     */
	public function __construct(TaskRepository $taskRepository, LoggerInterface $logger)
	{
		$this->taskRepository = $taskRepository;
		$this->logger = $logger;
	}
	
	/**
     * @param TaskDTO $taskDTO
     * @return bool
     */
	public function store(TaskDTO $taskDTO): bool
	{
		$task = new Task();

		$task->setTitle($taskDTO->title);
		$task->setDescription($taskDTO->description);
		$task->setCreatedAt(new DateTime());

		try{
			$this->taskRepository->store($task);

			return true;
		}catch(\Exception $e){
			$this->logger->error($e);
			return false;
		}
	}

	/**
     * @param int $id
     * @param TaskDTO $taskDTO
     * @return bool
     */
	public function update($id, TaskDTO $taskDTO): bool
	{
		$task = $this->taskRepository->find($id);

		$task->setTitle($taskDTO->title);
		$task->setDescription($taskDTO->description);
		$task->setUpdatedAt(new DateTime());

		try{
			$this->taskRepository->update($task);

			return true;
		}catch(\Exception $e){
			$this->logger->error($e);
			return false;
		}
	}
	
	/**
     * @param int $id
     * @param TaskDTO $taskDTO
     * @return bool
     */
	public function delete($id): bool
	{
		
		$task = $this->taskRepository->find($id);

		if(empty($task)){
			return false;
		}

		try{
			$this->taskRepository->delete($task);

			return true;
		}catch(\Exception $e){
			$this->logger->error($e);
			return false;
		}
	}

}