<?php

namespace App\Service;

use DateTime;
use Exception;
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
     * @return Task
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
     * @param integer $taskId
     * @param TaskDTO $taskDTO
     * @param integer $userId
     * @return Task
     */
	public function update(int $taskId, TaskDTO $taskDTO, int $userId): Task
	{
        $task = $this->taskRepository->find($taskId);

		if ( is_null($task) ) {
			throw new Exception("Task not found", 1);
        }

        if ( $task->getUser()->getId() !== $userId ) {
            throw new Exception("You can't update this task", 1);
        }

		$task->setTitle($taskDTO->title);
		$task->setDescription($taskDTO->description);
		$task->setUpdatedAt(new DateTime());

        $this->taskRepository->update($task);
        
        return $task;
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
