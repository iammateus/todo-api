<?php

namespace App\Entity\AccessToken;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class AccessToken
{
	/**
	 * @ORM\Id()
	 * @ORM\Column(type="string")
	 */
	private $id;

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\OneToOne(targetEntity="App\Entity\User\User")
	 */
	private $userId;

	/**
	 * @ORM\Column(type="string")
	 * @ORM\OneToOne(targetEntity="App\Entity\Client\Client")
	 */
	private $clientId;

	/**
	 * @ORM\Column(type="json_array")
	 */
	private $scopes;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $revoked;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $created_at;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $updated_at;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $expires_at;

	/**
	 * Token constructor.
	 * @param string $id
	 * @param string $userId
	 * @param string $clientId
	 * @param array $scopes
	 * @param bool $revoked
	 * @param \DateTime $created_at
	 * @param \DateTime $updated_at
	 * @param \DateTime $expires_at
	 */
	public function __construct(
		string $id,
		string $userId,
		string $clientId,
		array $scopes,
		bool $revoked,
		\DateTime $created_at,
		\DateTime $updated_at,
		\DateTime $expires_at
	) {
		$this->id = $id;
		$this->userId = $userId;
		$this->clientId = $clientId;
		$this->scopes = $scopes;
		$this->revoked = $revoked;
		$this->created_at = $created_at;
		$this->updated_at = $updated_at;
		$this->expires_at = $expires_at;
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return array
	 */
	public function getScopes()
	{
		return $this->scopes;
	}

	/**
	 * @param array $scopes
	 * @return self
	 */
	public function setScopes($scopes)
	{
		$this->scopes = $scopes;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function getRevoked()
	{
		return $this->revoked;
	}

	/**
	 * @param bool $revoked
	 * @return self
	 */
	public function setRevoked($revoked)
	{
		$this->revoked = $revoked;
		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreated_at()
	{
		return $this->created_at;
	}

	/**
	 * @param \DateTime $created_at
	 * @return self
	 */
	public function setCreated_at($created_at)
	{
		$this->created_at = $created_at;
		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getUpdated_at()
	{
		return $this->updated_at;
	}

	/**
	 * @param \DateTime $updated_at
	 * @return self
	 */
	public function setUpdated_at($updated_at)
	{
		$this->updated_at = $updated_at;
		return $this;
	}

	/**
	 * @return null|\DateTime
	 */
	public function getExpires_at()
	{
		return $this->expires_at;
	}

	/**
	 * @param \DateTime $expires_at
	 * @return self
	 */
	public function setExpires_at($expires_at)
	{
		$this->expires_at = $expires_at;
		return $this;
	}
}
