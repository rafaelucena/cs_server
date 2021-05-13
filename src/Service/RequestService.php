<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Validation;

class RequestService
{
    /** @var Request */
    private $request;

    /** @var bool */
    private $isValid = true;

    /** @var string */
    private $errors = '';

    /**
     * @param Request|null $request
     * @param int $id
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return bool
     */
    public function validateRequest(): bool
    {
        $validator = Validation::createValidator();

        $input = [];
        $constraint = new Collection([]);
        if ($this->request->getMethod() === 'GET') {
            $constraint = $this->getConstraintGet();
            $input = $this->request->query->all();
        } elseif ($this->request->getMethod() === 'POST') {
            $constraint = $this->getConstraintPost();
            $input = json_decode($this->request->getContent(), true);
        } elseif ($this->request->getMethod() === 'PUT') {
            $constraint = $this->getConstraintPut();
            $input = json_decode($this->request->getContent(), true);
        }
        $errors = $validator->validate($input, $constraint);

        if (count($errors) > 0) {
            $this->isValid = false;
            $this->errors = (string) $errors;

            return $this->isValid;
        }

        return $this->isValid;
    }

    /**
     * @return Collection
     */
    private function getConstraintGet(): Collection
    {
        return new Collection([
            'has_stock' => new Optional([
                new Choice(['false', 'true']),
            ]),
            'has_more_than' => new Optional([
                new Type('numeric'),
                new GreaterThanOrEqual(0),
                new NotNull(),
            ]),
        ]);
    }

    /**
     * @return Collection
     */
    private function getConstraintPost(): Collection
    {
        return new Collection([
            'name' => new Length(['min' => 1, 'max' => 127]),
            'amount' => new Required([
                new Type('numeric'),
                new GreaterThanOrEqual(0),
                new NotNull(),
            ]),
        ]);
    }

    /**
     * @return Collection
     */
    private function getConstraintPut(): Collection
    {
        return new Collection([
            'name' => new Optional([
                new Length(['min' => 1, 'max' => 127]),
            ]),
            'amount' => new Optional([
                new Type('numeric'),
                new GreaterThanOrEqual(0),
                new NotNull(),
            ]),
        ]);
    }

    /**
     * @return bool
     */
    public function getIsValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @return string
     */
    public function getErrors(): string
    {
        return $this->errors;
    }
}