<?php

namespace Plivo;

/**
 * Class User
 * @package Plivo
 */
class User extends Element
{
    /**
     * @var array
     */
    protected $nestables = [];
    /**
     * @var array
     */
    protected $valid_attributes = ['sendDigits', 'sendOnPreanswer', 'sipHeaders'];

    /**
     * User constructor.
     * @param string $body
     * @param array $attributes
     * @throws PlivoError
     */
    public function __construct($body, $attributes = [])
    {
        if (!$body) {
            throw new PlivoError("No user set for " . $this->getName());
        }

        parent::__construct($body, $attributes);
    }
}