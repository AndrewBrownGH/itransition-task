<?php declare(strict_types=1);

namespace AppBundle\Exception;

use Exception;
use Symfony\Component\Validator\ConstraintViolationList;

class CustomValidationException extends Exception
{
    /**
     * @var ConstraintViolationList
     */
    private $errors;

    public function __construct(ConstraintViolationList $constraintViolationList)
    {
        $this->errors = $constraintViolationList;
        parent::__construct($this->getErrorMessages());
    }

    public function getErrorMessages(): string
    {
        return (string)$this->errors;
    }
}