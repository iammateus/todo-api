<?php

namespace App\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\ArrayExpressibleEntityInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="user", schema="public")
 * @ORM\Entity()
 */
class User implements UserInterface, ArrayExpressibleEntityInterface
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $email;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $name;

	/**
	 * @ORM\Column(type="json_array")
	 */
	private $roles;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $password;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $active;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Task\Task", mappedBy="user")
	 */
	private $tasks;

	/**
	 * User contructor.
	 * @param string $email
	 * @param string $name
	 */
	public function __construct(string $email, string $name)
	{
		//@TODO: Make this contructor private and create a new method called "create" to instanciate a new User
		$this->email = $email;
		$this->name = $name;
		$this->roles = [];
		$this->active = true;
	}

	/**
	 * @return null|int
	 */
	public function getId(): ?int
	{
		return $this->id;
	}

	/**
	 * @return null|string
	 */
	public function getEmail(): ?string
	{
		return $this->email;
	}

	/**
	 * @param string $email
	 * @return self
	 */
	public function setEmail($email): self
	{
		$this->email = $email;
		return $this;
	}

	/**
	 * @return null|string
	 */
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return self
	 */
	public function setName($name): self
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return null|array
	 */
	public function getRoles(): ?array
	{
		return $this->roles;
	}

	/**
	 * @param array $roles
	 * @return self
	 */
	public function setRoles($roles): self
	{
		$this->roles = $roles;
		return $this;
	}

	/**
	 * @return null|string
	 */
	public function getPassword(): ?string
	{
		return $this->password;
	}

	/**
	 * @param string $password
	 * @return self
	 */
	public function setPassword($password): self
	{
		$this->password = $password;
		return $this;
	}

	/**
	 * @return null
	 */
	public function getSalt()
	{
		//You *may* need a real salt depending on your encodersee section on salt below
		return null;
	}

	/**
	 * @return null|string
	 */
	public function getUsername(): ?string
	{
		return $this->email;
	}

	/**
	 * @return null|bool
	 */
	public function getActive(): ?bool
	{
		return $this->active;
	}

	/**
	 * @param bool $active
	 * @return self
	 */
	public function setActive($active): self
	{
		$this->active = $active;
		return $this;
	}

	public function eraseCredentials()
	{ }

	/**
	 * @return array
	 */
	public function toArray(): array
	{
		// Controls the array format of object
		$array = [
			"id" => $this->id,
			"email" => $this->title,
			"name" => $this->description,
			"roles" => $this->created_at,
			"active" => $this->updated_at,
			"password" => $this->password
		];

		return  $array;
	}
}
