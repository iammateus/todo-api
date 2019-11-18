<?php

namespace App\Entity;

interface ArrayExpressibleEntityInterface
{
	public function toArray(): array;
}