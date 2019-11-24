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
	 * @param int $userId
	 * @return View
	 */
	public function store(TaskDTO $taskDTO, int $userId): View
	{
		$task = new Task();

		$task->setTitle($taskDTO->title);
		$task->setUser($this->userRepository->find($userId));
		$task->setDescription($taskDTO->description);
		$task->setCreatedAt(new DateTime());

		try {
			$this->taskRepository->store($task);
			return View::create(["data" => ["message" => "Task created", "taskId" => $task->getId()]], Response::HTTP_OK);
		} catch (\Exception $e) {
			$this->logger->error($e);
			return View::create(["data" => ["message" => "Failed to create a task"]], Response::HTTP_INTERNAL_SERVER_ERRORT);
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
