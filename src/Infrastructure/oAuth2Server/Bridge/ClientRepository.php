<?php

namespace App\Infrastructure\oAuth2Server\Bridge;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use Symfony\Component\Security\Core\Encoder\SodiumPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use App\Repository\ClientRepositoryInterface as AppClientRepositoryInterface;

final class ClientRepository implements ClientRepositoryInterface
{

	/**
	 * @var AppClientRepositoryInterface
	 */
	private $appClientRepository;

	/**
	 * ClientRepository constructor.
	 * @param AppClientRepositoryInterface $appClientRepository
	 */
	public function __construct(AppClientRepositoryInterface $appClientRepository)
	{
		$this->appClientRepository = $appClientRepository;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getClientEntity(
		$clientIdentifier,
		$grantType = null,
		$clientSecret = null
	): ?ClientEntityInterface {

		$appClient = $this->appClientRepository->findActive($clientIdentifier);

		if ($appClient === null) {
			return null;
		}

		$oauthClient = new Client($clientIdentifier, $appClient->getName(), $appClient->getRedirect());
		return $oauthClient;
	}

	public function validateClient($clientIdentifier, $clientSecret, $grantType)
	{
		$appClient = $this->appClientRepository->findActive($clientIdentifier);
		$passwordEncoder = new SodiumPasswordEncoder();

		if (!$passwordEncoder->isPasswordValid($appClient->getSecret(), $clientSecret, null)) {
			return false;
		}

		return true;
	}
}
