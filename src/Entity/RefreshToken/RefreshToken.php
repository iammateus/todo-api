<?php

namespace App\Entity\RefreshToken;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class RefreshToken
{
	/**
	 * @ORM\Id()
	 * @ORM\Column(type="string")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string")
	 * @ORM\OneToOne(targetEntity="App\Entity\AccessToken\AccessToken")
	 */
	private $accessTokenId;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $revoked;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $expires_at;

	/**
	 * RefreshToken constructor.
	 * @param string $id
	 * @param string $accessTokenId
	 * @param \DateTime $expiresAt
	 */
	public function __construct($id, $accessTokenId, \DateTime $expires_at)
	{
		$this->id = $id;
		$this->accessTokenId = $accessTokenId;
		$this->expires_at = $expires_at;
		$this->revoked = false;
	}

	/**
	 * @return string
	 */
	public function getAccessTokenId()
	{
		return $this->accessTokenId;
	}

	/**
	 * @return bool
	 */
	public function isRevoked(): bool
	{
		return $this->revoked;
	}

	/**
	 * @return void
	 */
	public function revoke(): void
	{
		$this->revoked = true;
	}

	/**
	 * @return \DateTime
	 */
	public function getExpiresAt(): \DateTime
	{
		return $this->expiresAt;
	}
}
