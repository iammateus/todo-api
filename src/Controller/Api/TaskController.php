<?php

namespace App\Controller\Api;

use App\Entity\Task\TaskDTO;
use App\Service\TaskService;
use FOS\RestBundle\View\View;
use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\TokenAuthenticatedController;
use Exception;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class TaskController extends AbstractFOSRestController implements TokenAuthenticatedController
{

    /**
     * Lists tasks.
     * 
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
     * 
	 * @Rest\Get("/tasks/{id}")
     * @param [type] $id
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
     * 
	 * @Rest\Post("/task")
	 * @ParamConverter("taskDTO", converter="fos_rest.request_body")
     * @param Request $request
     * @param TaskDTO $taskDTO
     * @param ConstraintViolationListInterface $validationErrors
     * @param TaskService $taskService
     * @return View
     */
	public function create(Request $request, TaskDTO $taskDTO, ConstraintViolationListInterface $validationErrors, TaskService $taskService): View
	{
		if ( count($validationErrors) ) {
			return View::create($validationErrors, Response::HTTP_BAD_REQUEST);
		}

        try 
        {
            $task = $taskService->store($taskDTO, $request->get('oauth_user_id'));
            return View::create(["message" => "Task created", "data" => ["taskId" => $task->getId()]], Response::HTTP_OK);
        }
        catch (\Exception $e)
        {
			$this->logger->error($e);
			return View::create(["error" => "Failed to create a task"], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
	}

    /**
     * Updates a task.
     * 
	 * @Rest\Put("/task/{id}")
	 * @ParamConverter("taskDTO", converter="fos_rest.request_body")
     * @param Request $request
     * @param TaskDTO $taskDTO
     * @param ConstraintViolationListInterface $validationErrors
     * @param TaskService $taskService
     * @return View
     */
	public function update(Request $request, TaskDTO $taskDTO, ConstraintViolationListInterface $validationErrors, TaskService $taskService): View
	{
		if ( count($validationErrors) ) {
			return View::create($validationErrors, Response::HTTP_BAD_REQUEST);
        }
        
        $userId = $request->get('oauth_user_id');
        $taskId = $request->get('id');

        try
        {
            $task = $taskService->update($taskId, $taskDTO, $userId);
            return View::create([ "message" => "Task updated successfully", "data" => [ "taskId" => $task->getId()] ], Response::HTTP_OK);
        }
        catch (Exception $error)
        {
            return View::create([ "error" => $error->getMessage() ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * Deletes a task.
     * 
	 * @Rest\Delete("/tasks/{id}")
     * @param [type] $id
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
