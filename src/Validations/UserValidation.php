<?php

namespace App\Validations;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class UserValidation
{

    public $email;
    public $name;
    public $password;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('email', new NotBlank());
        $metadata->addPropertyConstraint('email', new Email());

        $metadata->addPropertyConstraint('name', new NotBlank());
        
        $metadata->addPropertyConstraint('password', new NotBlank());
    }
}