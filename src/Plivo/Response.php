<?php

namespace Plivo;

/**
 * Class Response
 * @package Plivo
 */
class Response extends Element
{
    /**
     * @var array
     */
    protected $nestables = [
        'Speak',
        'Play',
        'GetDigits',
        'Record',
        'Dial',
        'Redirect',
        'Wait',
        'Hangup',
        'PreAnswer',
        'Conference',
        'DTMF',
        'Message',
    ];

    /**
     * Response constructor.
     */
    public function __construct()
    {
        parent::__construct(null);
    }

    /**
     * @param bool $header
     * @return mixed
     */
    public function toXML($header = false)
    {
        return parent::toXML(true);
    }
}
