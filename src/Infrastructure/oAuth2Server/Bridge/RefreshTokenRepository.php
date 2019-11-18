<?php

namespace App\Infrastructure\oAuth2Server\Bridge;

use App\Entity\RefreshToken\RefreshToken as AppRefreshToken;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use App\Infrastructure\oAuth2Server\Bridge\AccessTokenRepository;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use App\Repository\RefreshTokenRepositoryInterface as AppRefreshTokenRepositoryInterface;

final class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
	/**
	 * @var AppRefreshTokenRepositoryInterface
	 */
	private $appRefreshTokenRepository;

	/**
	 * @var AccessTokenRepository
	 */
	private $accessTokenRepository;

	/**
	 * RefreshTokenRepository constructor.
	 * @param AppRefreshTokenRepositoryInterface $appRefreshTokenRepository
	 * @param AccessTokenRepository $accessTokenRepository
	 */
	public function __construct(AppRefreshTokenRepositoryInterface $appRefreshTokenRepository, AccessTokenRepository $accessTokenRepository)
	{
		$this->appRefreshTokenRepository = $appRefreshTokenRepository;
		$this->accessTokenRepository = $accessTokenRepository;
	}
	/**
	 * {@inheritdoc}
	 */
	public function getNewRefreshToken(): RefreshTokenEntityInterface
	{
		return new RefreshToken();
	}

	/**
	 * {@inheritdoc}
	 */
	public function persistNewRefreshToken(RefreshTokenEntityInterface $refreshTokenEntity): void
	{
		$id = $refreshTokenEntity->getIdentifier();
		$accessTokenId = $refreshTokenEntity->getAccessToken()->getIdentifier();
		$expiryDateTime = new \DateTime("@{$refreshTokenEntity->getExpiryDateTime()->getTimeStamp()}");

		$refreshTokenPersistEntity = new AppRefreshToken($id, $accessTokenId, $expiryDateTime);
		$this->appRefreshTokenRepository->save($refreshTokenPersistEntity);
	}

	/**
	 * {@inheritdoc}
	 */
	public function revokeRefreshToken($tokenId): void
	{
		$refreshTokenPersistEntity = $this->appRefreshTokenRepository->find($tokenId);
		if ($refreshTokenPersistEntity === null) {
			return;
		}
		$refreshTokenPersistEntity->revoke();
		$this->appRefreshTokenRepository->save($refreshTokenPersistEntity);
	}

	/**
	 * {@inheritdoc}
	 */
	public function isRefreshTokenRevoked($tokenId): bool
	{
		$refreshTokenPersistEntity = $this->appRefreshTokenRepository->find($tokenId);
		if ($refreshTokenPersistEntity === null || $refreshTokenPersistEntity->isRevoked()) {
			return true;
		}
		return $this->accessTokenRepository->isAccessTokenRevoked($refreshTokenPersistEntity->getAccessTokenId());
	}
}