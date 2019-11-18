<?php

namespace App\Repository;

use App\Entity\User\User;

interface UserRepositoryInterface
{
	public function find(int $id): ?User;
	public function findOneByEmail(string $email): ?User;
	public function store(User $user);
	public function update(User $user);
	public function delete(User $user);
}
