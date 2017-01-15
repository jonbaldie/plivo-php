<?php

namespace Plivo;

/**
 * Class Element
 * @package Plivo
 */
class Element
{
    /**
     * @var array
     */
    protected $nestables = [];
    /**
     * @var array
     */
    protected $valid_attributes = [];
    /**
     * @var array
     */
    protected $attributes = [];
    /**
     * @var mixed
     */
    protected $name;
    /**
     * @var null|string
     */
    protected $body = null;
    /**
     * @var array
     */
    protected $childs = [];

    /**
     * Element constructor.
     * @param string $body
     * @param array $attributes
     * @throws PlivoError
     */
    public function __construct($body = '', $attributes = [])
    {
        $this->attributes = $attributes;

        if ((!$attributes) || ($attributes === null)) {
            $this->attributes = [];
        }

        $this->name = preg_replace('/^' . __NAMESPACE__ . '\\\\/', '', get_class($this));
        //$this->name = get_class($this);

        $this->body = $body;

        foreach ($this->attributes as $key => $value) {
            if (!in_array($key, $this->valid_attributes)) {
                throw new PlivoError("invalid attribute " . $key . " for " . $this->name);
            }

            $this->attributes[$key] = $this->convert_value($value);
        }
    }

    /**
     * @param $v
     * @return string
     */
    protected function convert_value($v)
    {
        if ($v === true) {
            return "true";
        }

        if ($v === false) {
            return "false";
        }

        if ($v === null) {
            return "none";
        }

        if ($v === "get") {
            return "GET";
        }

        if ($v === "post") {
            return "POST";
        }

        return $v;
    }

    /**
     * @param null $body
     * @param array $attributes
     * @return mixed
     */
    public function addSpeak($body = null, $attributes = [])
    {
        return $this->add(new Speak($body, $attributes));
    }

    /**
     * @param null $body
     * @param array $attributes
     * @return mixed
     */
    public function addPlay($body = null, $attributes = [])
    {
        return $this->add(new Play($body, $attributes));
    }

    /**
     * @param null $body
     * @param array $attributes
     * @return mixed
     */
    public function addDial($body = null, $attributes = [])
    {
        return $this->add(new Dial($body, $attributes));
    }

    /**
     * @param null $body
     * @param array $attributes
     * @return mixed
     */
    public function addNumber($body = null, $attributes = [])
    {
        return $this->add(new Number($body, $attributes));
    }

    /**
     * @param null $body
     * @param array $attributes
     * @return mixed
     */
    public function addUser($body = null, $attributes = [])
    {
        return $this->add(new User($body, $attributes));
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function addGetDigits($attributes = [])
    {
        return $this->add(new GetDigits($attributes));
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function addRecord($attributes = [])
    {
        return $this->add(new Record($attributes));
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function addHangup($attributes = [])
    {
        return $this->add(new Hangup($attributes));
    }

    /**
     * @param null $body
     * @param array $attributes
     * @return mixed
     */
    public function addRedirect($body = null, $attributes = [])
    {
        return $this->add(new Redirect($body, $attributes));
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function addWait($attributes = [])
    {
        return $this->add(new Wait($attributes));
    }

    /**
     * @param null $body
     * @param array $attributes
     * @return mixed
     */
    public function addConference($body = null, $attributes = [])
    {
        return $this->add(new Conference($body, $attributes));
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function addPreAnswer($attributes = [])
    {
        return $this->add(new PreAnswer($attributes));
    }

    /**
     * @param null $body
     * @param array $attributes
     * @return mixed
     */
    public function addMessage($body = null, $attributes = [])
    {
        return $this->add(new Message($body, $attributes));
    }

    /**
     * @param null $body
     * @param array $attributes
     * @return mixed
     */
    public function addDTMF($body = null, $attributes = [])
    {
        return $this->add(new DTMF($body, $attributes));
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $element
     * @return mixed
     * @throws PlivoError
     */
    protected function add($element)
    {
        if (!in_array($element->getName(), $this->nestables)) {
            throw new PlivoError($element->getName() . " not nestable in " . $this->getName());
        }

        $this->childs[] = $element;

        return $element;
    }

    /**
     * @param $xml
     */
    public function setAttributes($xml)
    {
        foreach ($this->attributes as $key => $value) {
            $xml->addAttribute($key, $value);
        }
    }

    /**
     * @param $xml
     */
    public function asChild($xml)
    {
        if ($this->body) {
            $child_xml = $xml->addChild($this->getName(), htmlspecialchars($this->body));
        } else {
            $child_xml = $xml->addChild($this->getName());
        }

        $this->setAttributes($child_xml);

        foreach ($this->childs as $child) {
            $child->asChild($child_xml);
        }
    }

    /**
     * @param bool $header
     * @return mixed
     */
    public function toXML($header = false)
    {
        if (!(isset($xmlstr))) {
            $xmlstr = '';
        }

        if ($this->body) {
            $xmlstr .= "<" . $this->getName() . ">" . htmlspecialchars($this->body) . "</" . $this->getName() . ">";
        } else {
            $xmlstr .= "<" . $this->getName() . "></" . $this->getName() . ">";
        }

        if ($header === true) {
            $xmlstr = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>" . $xmlstr;
        }

        $xml = new \SimpleXMLElement($xmlstr);
        $this->setAttributes($xml);

        foreach ($this->childs as $child) {
            $child->asChild($xml);
        }

        return $xml->asXML();
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->toXML();
    }
}
