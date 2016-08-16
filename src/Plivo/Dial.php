<?php

namespace Plivo;

class Dial extends Element
{
    protected $nestables = ['Number', 'User'];
    protected $valid_attributes = [
        'action',
        'method',
        'timeout',
        'hangupOnStar',
        'timeLimit',
        'callerId',
        'callerName',
        'confirmSound',
        'dialMusic',
        'confirmKey',
        'redirect',
        'callbackUrl',
        'callbackMethod',
        'digitsMatch',
        'digitsMatchBLeg',
        'sipHeaders',
    ];

    function __construct($attributes = [])
    {
        parent::__construct(null, $attributes);
    }
}