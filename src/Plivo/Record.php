<?php

namespace Plivo;

/**
 * Class Record
 * @package Plivo
 */
class Record extends Element
{
    /**
     * @var array
     */
    protected $nestables = [];
    /**
     * @var array
     */
    protected $valid_attributes = [
        'action',
        'method',
        'timeout',
        'finishOnKey',
        'maxLength',
        'playBeep',
        'recordSession',
        'startOnDialAnswer',
        'redirect',
        'fileFormat',
        'callbackUrl',
        'callbackMethod',
        'transcriptionType',
        'transcriptionUrl',
        'transcriptionMethod',
    ];

    /**
     * Record constructor.
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct(null, $attributes);
    }
}