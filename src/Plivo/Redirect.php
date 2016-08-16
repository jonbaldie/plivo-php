<?php
/**
 * Created by PhpStorm.
 * User: Jon
 * Date: 16/08/2016
 * Time: 10:15
 */

namespace Plivo;

class Redirect extends Element
{
    protected $nestables = [];
    protected $valid_attributes = ['method'];

    function __construct($body, $attributes = [])
    {
        parent::__construct($body, $attributes);
        if (!$body) {
            throw new PlivoError("No url set for " . $this->getName());
        }
    }
}