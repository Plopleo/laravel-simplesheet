<?php

namespace Nikazooz\Simplesheet\Validators;

use Illuminate\Validation\ValidationException as IlluminateValidationException;

class ValidationException extends IlluminateValidationException
{
    /**
     * @var \Nikazooz\Simplesheet\Validators\Failure[]
     */
    protected $failures;

    /**
     * @param  \Illuminate\Validation\ValidationException  $previous
     * @param  array  $failures
     */
    public function __construct(IlluminateValidationException $previous, array $failures)
    {
        parent::__construct($previous->validator, $previous->response, $previous->errorBag);
        $this->failures = $failures;
    }

    /**
     * @return string[]
     */
    public function errors(): array
    {
        return collect($this->failures)->map->toArray()->all();
    }

    /**
     * @return array
     */
    public function failures(): array
    {
        return $this->failures;
    }
}