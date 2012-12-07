<?php

namespace Coo\SimpleWorkflowManager\Step;

/**
 * Stepper class.
 *
 * @author Florent Mondoloni
 * @author Cédric Dugas
 */
class Step
{
    private $key;
    private $isCurrent = false;
    private $properties = array();

    /**
     * setKey setter key.
     * 
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * getKey getter key.
     * 
     * @return string $key
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * addProperty
     *
     * @param string $key
     * @param mixed $property
     * @return Coo\SimpleWorkflowManager\Step\Step
     */
    public function addProperty($key, $property)
    {
        $this->properties[$key] = $property;
        
        return $this;
    }

    /**
     * getProperty
     *
     * @param string $key
     * @return mixed
     */
    public function getProperty($key)
    {
        if (!array_key_exists($key, $this->properties)) {
            throw new \InvalidArgumentException(sprintf("Property %s does not exist", $key));
        }

        return $this->properties[$key];
    }

    /**
     * setIsCurrent
     * @param  boolean $isCurrent
     */
    public function setIsCurrent($isCurrent)
    {
        $this->isCurrent = $isCurrent;
    }

    /**
     * isCurrent
     * @return boolean
     */
    public function isCurrent()
    {
        return $this->isCurrent;
    }

    /**
     * __toString
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->getKey();
    }
}
