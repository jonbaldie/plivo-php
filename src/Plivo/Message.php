<?php

namespace Plivo;

/**
 * Class Message
 * @package Plivo
 */
class Message extends Element
{
    /**
     * @var array
     */
    protected $nestables = [];
    /**
     * @var array
     */
    protected $valid_attributes = ['src', 'dst', 'type', 'callbackMethod', 'callbackUrl'];

    /**
     * Message constructor.
     * @param string $body
     * @param array $attributes
     * @throws PlivoError
     */
    public function __construct($body, $attributes = [])
    {
        if (!$body) {
            throw new PlivoError("No text set for " . $this->getName());
        }

        parent::__construct($body, $attributes);
    }
}