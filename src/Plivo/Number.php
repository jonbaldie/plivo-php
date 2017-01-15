<?php

namespace Plivo;

/**
 * Class Number
 * @package Plivo
 */
class Number extends Element
{
    /**
     * @var array
     */
    protected $nestables = [];
    /**
     * @var array
     */
    protected $valid_attributes = ['sendDigits', 'sendOnPreanswer', 'sendDigitsMode'];

    /**
     * Number constructor.
     * @param string $body
     * @param array $attributes
     * @throws PlivoError
     */
    public function __construct($body, $attributes = [])
    {
        if (!$body) {
            throw new PlivoError("No number set for " . $this->getName());
        }

        parent::__construct($body, $attributes);
    }
}