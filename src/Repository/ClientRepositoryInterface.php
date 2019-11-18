<?php

namespace App\Repository;

use App\Entity\Client\Client;

interface ClientRepositoryInterface
{
	public function findActive(string $id): ?Client;
	public function store(Client $client);
	public function update(Client $client);
	public function delete(Client $client);
}
