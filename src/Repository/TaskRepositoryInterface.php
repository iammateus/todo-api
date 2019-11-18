<?php

namespace App\Repository;

use App\Entity\Task\Task;

interface TaskRepositoryInterface
{
	public function find(int $taskId);
	public function findAll(int $limit,int $offset): array;
	public function findAllByTitle(string $title): array;
	public function store(Task $task);
	public function update(Task $task);
	public function delete(Task $task);
}
