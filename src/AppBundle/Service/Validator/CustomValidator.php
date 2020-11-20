<?php declare(strict_types=1);

namespace AppBundle\Service\Validator;

use AppBundle\Exception\CustomValidationException;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CustomValidator
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate($value): void
    {
        /**
         * @var ConstraintViolationList $errors
         */
        $errors = $this->validator->validate($value);
        if (count($errors) > 0) {
            throw new CustomValidationException($errors);
        }
    }
}