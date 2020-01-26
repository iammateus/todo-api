<?php

namespace App\Entity\Client;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Client
{
	/**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $name;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $secret;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $redirect;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $active;

	/**
	 * Client constructor.
	 * @param string $id
	 */
	private function __construct(string $name)
	{
		$this->name = $name;
	}

	/**
	 * Creates a new client.
	 * @param string $name
	 * @return Client
	 */
	public static function create(string $name): Client
	{
		return new self($name);
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return self
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string 
	 */
	public function getSecret()
	{
		return $this->secret;
	}

	/**
	 * @param string $secret
	 * @return self
	 */
	public function setSecret($secret)
	{
		$this->secret = $secret;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getRedirect()
	{
		return $this->redirect;
	}

	/**
	 * @param string $redirect
	 * @return self
	 */
	public function setRedirect($redirect)
	{
		$this->redirect = $redirect;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function getActive()
	{
		return $this->active;
	}

	/**
	 * @param bool $active
	 * @return self
	 */
	public function setActive($active)
	{
		$this->active = $active;
		return $this;
	}
}
