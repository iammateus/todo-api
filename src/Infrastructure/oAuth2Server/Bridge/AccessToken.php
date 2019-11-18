<?php

namespace App\Infrastructure\oAuth2Server\Bridge;

use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;

final class AccessToken implements AccessTokenEntityInterface
{
	use AccessTokenTrait, EntityTrait, TokenEntityTrait;
	/**
	 * AccessToken constructor.
	 * @param ClientEntityInterface $clientEntity
	 * @param string $userIdentifier
	 * @param array $scopes
	 */
	public function __construct(ClientEntityInterface $clientEntity, string $userIdentifier, array $scopes = [])
	{
		$this->setClient($clientEntity);
		$this->setUserIdentifier($userIdentifier);
		foreach ($scopes as $scope) {
			$this->addScope($scope);
		}
	}

	/**
	 * @param ClientEntityInterface $client
	 * @return self
	 */
	public function setClient(ClientEntityInterface $client)
	{
		$this->client = $client;
		return $this;
	}
}
