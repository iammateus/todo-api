<?php

namespace App\Service;

use DateTime;
use App\Entity\Task\Task;
use App\Entity\Task\TaskDTO;
use Psr\Log\LoggerInterface;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
	 * @param TaskRepository $taskRepository
	 * @param UserRepository $userRepository
	 * @param LoggerInterface $logger
	 * @return void
	 */
	public function __construct(TaskRepository $taskRepository, UserRepository $userRepository)
	{
		$this->taskRepository = $taskRepository;
		$this->userRepository = $userRepository;
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
			throw new BadRequestHttpException("Task not found");
        }

        if ( $task->getUser()->getId() !== $userId ) {
            throw new BadRequestHttpException("You can't update this task");
        }

		$task->setTitle($taskDTO->title);
		$task->setDescription($taskDTO->description);
		$task->setUpdatedAt(new DateTime());

        $this->taskRepository->update($task);
        
        return $task;
	}

	/**
     * @param integer $taskId
     * @param integer $userId
     */
	public function delete(int $taskId, int $userId)
	{
		$task = $this->taskRepository->find($taskId);

		if ( is_null($task) ) {
			throw new BadRequestHttpException("Task not found");
        }

        if ( $task->getUser()->getId() !== $userId ) {
            throw new BadRequestHttpException("You can't delete this task");
        }

        $this->taskRepository->delete($task);
	}
}
