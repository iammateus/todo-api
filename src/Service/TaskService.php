<?php

namespace App\Service;

use DateTime;
use App\Entity\Task\Task;
use App\Entity\Task\TaskDTO;
use Psr\Log\LoggerInterface;
use FOS\RestBundle\View\View;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;

final class TaskService
{
	/**
	 * @var TaskRepository
	 */
	private $taskRepository;

	/**
	 * @var UserRepository
	 */
	private $userRepository;

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @param TaskRepository $taskRepository
	 * @param UserRepository $userRepository
	 * @param LoggerInterface $logger
	 * @return void
	 */
	public function __construct(TaskRepository $taskRepository, UserRepository $userRepository, LoggerInterface $logger)
	{
		$this->taskRepository = $taskRepository;
		$this->userRepository = $userRepository;
		$this->logger = $logger;
	}

	/**
     * @param TaskDTO $taskDTO
     * @param integer $userId
     * @return View
     */
	public function store(TaskDTO $taskDTO, int $userId): Task
	{
        $task = new Task();
        
        $user = $this->userRepository->find($userId);

        $task->setTitle($taskDTO->title);
		$task->setUser($user);
		$task->setDescription($taskDTO->description);
        $task->setCreatedAt(new DateTime());

        $this->taskRepository->store($task);
        
        return $task;
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

		try {
			$this->taskRepository->update($task);

			return true;
		} catch (\Exception $e) {
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

		if (empty($task)) {
			return false;
		}

		try {
			$this->taskRepository->delete($task);

			return true;
		} catch (\Exception $e) {
			$this->logger->error($e);
			return false;
		}
	}
}
