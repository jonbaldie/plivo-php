<?php

namespace Plivo;

/**
 * Class GetDigits
 * @package Plivo
 */
class GetDigits extends Element
{
    /**
     * @var array
     */
    protected $nestables = ['Speak', 'Play', 'Wait'];
    /**
     * @var array
     */
    protected $valid_attributes = [
        'action',
        'method',
        'timeout',
        'digitTimeout',
        'numDigits',
        'retries',
        'invalidDigitsSound',
        'validDigits',
        'playBeep',
        'redirect',
        "finishOnKey",
        'digitTimeout',
        'log',
    ];

    /**
     * GetDigits constructor.
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct(null, $attributes);
    }
}