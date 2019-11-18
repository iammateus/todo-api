<?php

namespace App\Repository;

use App\Entity\AccessToken\AccessToken;

interface AccessTokenRepositoryInterface
{
	public function find($id): ?AccessToken;
	public function save(AccessToken $accessToken): void;
}
