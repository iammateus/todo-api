<?php

namespace App\Controller\Api;

/* 
The imports below is used in the revokeAccessToken method

use App\Repository\AccessTokenRepositoryInterface;
use Lcobucci\JWT\Parser;
*/

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response as Psr7Response;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\PasswordGrant;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AuthController extends AbstractController
{
	/**
	 * @var AuthorizationServer
	 */
	private $authorizationServer;

	/**
	 * @var PasswordGrant
	 */
	private $passwordGrant;

	/**
	 * @var RefreshTokenGrant
	 */
	private $refreshTokenGrant;

	/**
	 * AuthController constructor.
	 * @param AuthorizationServer $authorizationServer
	 * @param PasswordGrant $passwordGrant
	 * @param RefreshTokenGrant $refreshTokenGrant
	 */
	public function __construct(
		AuthorizationServer $authorizationServer,
		PasswordGrant $passwordGrant,
		RefreshTokenGrant $refreshTokenGrant
	) {
		$this->authorizationServer = $authorizationServer;
		$this->passwordGrant = $passwordGrant;
		$this->refreshTokenGrant = $refreshTokenGrant;
	}

	/**
	 * @Route("accessToken", name="api_get_access_token", methods={"POST"})
	 * @param ServerRequestInterface $request
	 * @return null|Psr7Response
	 * @throws \Exception
	 */
	public function getAccessToken(ServerRequestInterface $request): ?Psr7Response
	{
		$this->passwordGrant->setRefreshTokenTTL(new \DateInterval('P1M'));
		$this->refreshTokenGrant->setRefreshTokenTTL(new \DateInterval('P1M'));

		return $this->withErrorHandling(function () use ($request) {
			$this->passwordGrant->setRefreshTokenTTL(new \DateInterval('P1M'));
			$this->authorizationServer->enableGrantType(
				$this->passwordGrant,
				new \DateInterval('PT1H')
			);
			$this->authorizationServer->enableGrantType(
				$this->refreshTokenGrant,
				new \DateInterval('PT1H')
			);
			return $this->authorizationServer->respondToAccessTokenRequest($request, new Psr7Response());
		});
	}

	/**
	 * 
	 * //@Route("revokeAccessToken", name="api_get_revoke_access_token", methods={"POST"})
	 * @param ServerRequestInterface $request
	 * @return null|Psr7Response
	 * @throws \Exception
	 */
	/*

	I don't know how much a good idea is it to create something like this (handling the token "manually"),
	so this function will stay commented in order to be a example of how to parse a token and revoke it 

	public function revokeAccessToken(ServerRequestInterface $request, AccessTokenRepositoryInterface $atr): ?Psr7Response
	{
		$header = $request->getHeader('authorization');
		$jwt = trim((string) preg_replace('/^(?:\s+)?Bearer\s/', '', $header[0]));
		$token = (new Parser())->parse($jwt);
		$token = $atr->find($token->getClaim('jti'));
		$token->setRevoked(true);
		$atr->save($token);
	}
	*/

	/**
	 * @param $callback
	 * @return null|Psr7Response
	 */
	private function withErrorHandling($callback): ?Psr7Response
	{
		try {
			return $callback();
		} catch (OAuthServerException $e) {
			return $this->convertResponse(
				$e->generateHttpResponse(new Psr7Response())
			);
		} catch (\Exception $e) {
			return new Psr7Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
		} catch (\Throwable $e) {
			return new Psr7Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	/**
	 * @param Psr7Response $psrResponse
	 * @return Psr7Response
	 */
	private function convertResponse(Psr7Response $psrResponse): Psr7Response
	{
		return new Psr7Response(
			$psrResponse->getBody(),
			$psrResponse->getStatusCode(),
			$psrResponse->getHeaders()
		);
	}
}
