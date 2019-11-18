<?php

namespace App\Entity\Task;

use App\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\ArrayExpressibleEntityInterface;

/**
 * @ORM\Entity()
 */
class Task implements ArrayExpressibleEntityInterface
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User\User", inversedBy="tasks")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
	 */
	private $user;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $title;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $description;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $created_at;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $updated_at;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $deleted_at;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getTitle(): ?string
	{
		return $this->title;
	}

	public function setTitle(string $title): self
	{
		$this->title = $title;

		return $this;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription(?string $description): self
	{
		$this->description = $description;

		return $this;
	}

	public function getCreatedAt(): ?\DateTimeInterface
	{
		return $this->created_at;
	}

	public function setCreatedAt(\DateTimeInterface $created_at): self
	{
		$this->created_at = $created_at;

		return $this;
	}

	public function getUpdatedAt(): ?\DateTimeInterface
	{
		return $this->updated_at;
	}

	public function setUpdatedAt(?\DateTimeInterface $updated_at): self
	{
		$this->updated_at = $updated_at;

		return $this;
	}

	public function getDeletedAt(): ?\DateTimeInterface
	{
		return $this->deleted_at;
	}

	public function setDeletedAt(?\DateTimeInterface $deleted_at): self
	{
		$this->deleted_at = $deleted_at;

		return $this;
	}

	public function getUser(): ?User
	{
		return $this->user;
	}

	public function setUser(?User $user)
	{
		$this->user = $user;
		return $this;
	}

	public function toArray(): array
	{
		// Controls the array format of object

		$array = [
			"id" => $this->id,
			"title" => $this->title,
			"description" => $this->description,
			"created_at" => $this->created_at,
			"updated_at" => $this->updated_at,
		];

		return  $array;
	}
}
