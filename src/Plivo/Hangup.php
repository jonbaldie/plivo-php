<?php

namespace Plivo;

/**
 * Class Hangup
 * @package Plivo
 */
class Hangup extends Element
{
    /**
     * @var array
     */
    protected $nestables = [];
    /**
     * @var array
     */
    protected $valid_attributes = ['schedule', 'reason'];

    /**
     * Hangup constructor.
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct(null, $attributes);
    }
}