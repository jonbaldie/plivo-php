<?php

namespace Plivo;

/**
 * Class PreAnswer
 * @package Plivo
 */
class PreAnswer extends Element
{
    /**
     * @var array
     */
    protected $nestables = ['Play', 'Speak', 'GetDigits', 'Wait', 'Redirect', 'Message', 'DTMF'];
    /**
     * @var array
     */
    protected $valid_attributes = [];

    /**
     * PreAnswer constructor.
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct(null, $attributes);
    }
}