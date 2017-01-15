<?php

namespace Plivo;

/**
 * Class Play
 * @package Plivo
 */
class Play extends Element
{
    /**
     * @var array
     */
    protected $nestables = [];
    /**
     * @var array
     */
    protected $valid_attributes = ['loop'];

    /**
     * Play constructor.
     * @param string $body
     * @param array $attributes
     * @throws PlivoError
     */
    public function __construct($body, $attributes = [])
    {
        if (!$body) {
            throw new PlivoError("No url set for " . $this->getName());
        }

        parent::__construct($body, $attributes);
    }
}