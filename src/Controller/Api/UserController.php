<?php

namespace App\Controller\Api;

use App\Entity\User\User;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends FOSRestController
{
	/**
	 * Get an user object.
	 * @Rest\Get("/user/get-an-user-object")
	 * @param UserPasswordEncoderInterface $encoder
	 * @return View
	 */
	public function getAnUserObject(UserPasswordEncoderInterface $encoder): View
	{
		$user = new User("email", "email");
		$encoded = $encoder->encodePassword($user, "12345");

		$user->setPassword($encoded);

		return View::create(
			[
				"data" => (array) $user,
				"errors" => []
			],
			Response::HTTP_OK
		);
	}
}