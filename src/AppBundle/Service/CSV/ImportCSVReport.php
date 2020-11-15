<?php declare(strict_types=1);

namespace AppBundle\Service\CSV;


class ImportCSVReport
{
    /**
     * @var int
     */
    private $processed = 0;

    /**
     * @var array
     */
    private $errors = [];

    public function increaseProcessed()
    {
        $this->processed++;
    }

    public function addError(string $message, string $product): void
    {
        $this->errors[] = [
            'message' => $message,
            'product' => $product,
        ];
    }

    public function getErrors(): ?array
    {
        return $this->errors;
    }

    public function getProcessed(): int
    {
        return $this->processed;
    }

    public function getCountErrors(): int
    {
        return count($this->errors);
    }

    public function getSuccessful(): int
    {
        return $this->getProcessed() - $this->getCountErrors();
    }
}