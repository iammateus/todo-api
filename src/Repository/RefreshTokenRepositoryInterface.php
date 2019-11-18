<?php

namespace App\Repository;

use App\Entity\RefreshToken\RefreshToken;

interface RefreshTokenRepositoryInterface
{
	public function find(string $id): ?RefreshToken;
	public function save(RefreshToken $accessToken): void;
}
