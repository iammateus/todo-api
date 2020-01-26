<?php

namespace App\Controller\Api;

use App\Entity\User\User;
use App\Repository\UserRepository;
use App\Validations\UserValidation;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractFOSRestController
{
    /**
     * @var ValidatorInterface
     */
    private $validator;
    
    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator) {
        $this->validator = $validator;
    }
	/**
	 * Get an user object.
	 * @Rest\Post("/user")
	 * @param Request $request
	 * @param UserPasswordEncoderInterface $encoder
	 * @return View
	 */
	public function store(Request $request, UserPasswordEncoderInterface $encoder, UserValidation $userValidation, UserRepository $userRepository): View
	{
        $userValidation->email = $request->get("email");
        $userValidation->name = $request->get("name");
        $userValidation->password = $request->get("password");

        $errors = $this->validator->validate($userValidation);

        if (count($errors) > 0) {
            return View::create([
                "error" =>  $errors->get(0)->getPropertyPath() . ": "  . $errors->get(0)->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = new User($request->get("email"), $request->get("name"));
        
        $encoded = $encoder->encodePassword($user, "12345");
        $user->setPassword($encoded);

        $userRepository->store($user);

		return View::create([
            "message" => "User created"
        ], Response::HTTP_OK);
	}
}
