<?php

namespace App\Entity\Task;

use Symfony\Component\Validator\Constraints as Assert;

final class TaskDTO
{

	/**
	 * @var int
	 */
	public $id;

	/**
	 * @var \App\Entity\User\User
	 */
	public $user;

	/**
	 * @Assert\NotBlank(message="O campo título é obrigatório.")
	 * @var string
	 */
	public $title;

	/**
	 * @var string
	 */
	public $description;

	/**
	 * @var date
	 */
	public $created_at;

	/**
	 * @var date
	 */
	public $updated_at;

	/**
	 * @var date
	 */
	public $deleted_at;
}
