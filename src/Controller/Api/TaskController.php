<?php

namespace App\Controller\Api;

use App\Entity\Task\TaskDTO;
use App\Service\TaskService;
use FOS\RestBundle\View\View;
use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\TokenAuthenticatedController;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class TaskController extends FOSRestController implements TokenAuthenticatedController
{
	/**
	 * Lists tasks.
	 * @Rest\Get("/tasks")
	 * @param Request $request
	 * @param TaskRepository $taskRepository
	 * @return View
	 */
	public function index(Request $request, TaskRepository $taskRepository): View
	{

		$id = $request->get('oauth_user_id');

		$limit = empty((int) $request->get("limit")) ? 20 : (int) $request->get("limit");
		$offset = (int) $request->get("offset");

		$tasks = $taskRepository->findAll($limit, $offset);

		$toArrayTasks = [];

		foreach ($tasks as $task) {
			$toArrayTasks[] = $task->toArray();
		}

		return View::create(
			[
				"data" => $toArrayTasks ?? [],
				"errors" => !empty($toArrayTasks) ? [] : ["message" => "Tasks not found"]
			],
			!empty($toArrayTasks) ? Response::HTTP_OK : Response::HTTP_NOT_FOUND
		);
	}

	/**
	 * Shows a single task.
	 * @Rest\Get("/tasks/{id}")
	 * @param int $id
	 * @param TaskRepository $taskRepository
	 * @return View
	 */
	public function show($id, TaskRepository $taskRepository): View
	{
		$task = $taskRepository->find($id);

		return View::create(
			[
				"data" => !empty($task) ? $task->toArray() : [],
				"errors" => !empty($task) ? [] : ["message" => "Task not found"]
			],
			!empty($task) ? Response::HTTP_OK : Response::HTTP_NOT_FOUND
		);
	}

	/**
	 * Creates a task.
	 * @Rest\Post("/tasks")
	 * @ParamConverter("taskDTO", converter="fos_rest.request_body")
	 * @param TaskDTO $taskDTO
	 * @param ConstraintViolationListInterface $validationErrors
	 * @param TaskService $taskService
	 * @return View
	 */
	public function create(TaskDTO $taskDTO, ConstraintViolationListInterface $validationErrors, TaskService $taskService): View
	{

		if (count($validationErrors)) {
			return View::create($validationErrors, Response::HTTP_BAD_REQUEST);
		}

		$storeTaskReturn = $taskService->store($taskDTO);

		return View::create(
			[
				"data" => $storeTaskReturn ? ["message" => "Task created successfully"] : [],
				"errors" => $storeTaskReturn ? [] : ["message" => "Error while creating task"]
			],
			$storeTaskReturn ? Response::HTTP_OK : Response::HTTP_INTERNAL_SERVER_ERROR
		);
	}

	/**
	 * Updates a task.
	 * @Rest\Put("/tasks/{id}")
	 * @ParamConverter("taskDTO", converter="fos_rest.request_body")
	 * @param int $id
	 * @param TaskDTO $taskDTO
	 * @param ConstraintViolationListInterface $validationErrors
	 * @param TaskRepository $taskRepository
	 * @param TaskService $taskService
	 * @return View
	 */
	public function update($id, TaskDTO $taskDTO, ConstraintViolationListInterface $validationErrors, TaskRepository $taskRepository, TaskService $taskService): View
	{
		if (count($validationErrors)) {
			return View::create($validationErrors, Response::HTTP_BAD_REQUEST);
		}

		$task = $taskRepository->find($id);

		if (empty($task)) {
			return View::create(
				[
					"data" => [],
					"errors" => ["message" => "Task not found"]
				],
				Response::HTTP_NOT_FOUND
			);
		}

		$updateTaskReturn = $taskService->update($id, $taskDTO);

		return View::create(
			[
				"data" => $updateTaskReturn ? ["message" => "Task updated successfully"] : [],
				"errors" => $updateTaskReturn ? [] : ["message" => "Error while updating task"]
			],
			$updateTaskReturn ? Response::HTTP_OK : Response::HTTP_INTERNAL_SERVER_ERROR
		);
	}
	/**
	 * Deletes a task.
	 * @Rest\Delete("/tasks/{id}")
	 * @param int $id
	 * @param TaskService $taskService
	 * @return View
	 */
	public function delete($id, TaskService $taskService): View
	{

		$deleteTaskReturn = $taskService->delete($id);

		return View::create(
			[
				"data" => $deleteTaskReturn ? ["message" => "Task deleted successfully"] : [],
				"errors" => $deleteTaskReturn ? [] : ["message" => "Error while deleting task"]
			],
			$deleteTaskReturn ? Response::HTTP_OK : Response::HTTP_INTERNAL_SERVER_ERROR
		);
	}
}
