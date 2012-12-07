<?php

namespace Coo\SimpleWorkflowManager\Step;

use Coo\SimpleWorkflowManager\Exception\ConfigurationException;
use Countable;
use IteratorAggregate;
use ArrayAccess;

/**
 * StepCollection Step Object Collection.
 * 
 * @author Florent Mondoloni
 * @author Cédric Dugat
 */
class StepCollection implements Countable, IteratorAggregate, ArrayAccess
{
    /**
     * An array containing the entries of this collection.
     */
    private $steps;

    /**
     * __construct
     *
     * @param array $steps
     */
    public function __construct(array $steps = array())
    {
        $this->steps = $steps;
    }

    /**
     * getIterator
     * Gets an iterator for iterating over the steps in the collection.
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->steps);
    }

    /**
     * getSteps
     * 
     * @return array()
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * updateCurrent
     * 
     * @param  Coo\SimpleWorkflowManager\Step\Step $currentStep
     */
    public function updateCurrent(Step $currentStep)
    {
        foreach ($this as $step) {
            $currentStep->setIsCurrent($currentStep === $step);
        }
    }

    /**
     * add
     * @param Coo\SimpleWorkflowManager\Step\Step $step
     */
    public function add(Step $step)
    {
        if ($this->containsKey($step->getKey())) {
            throw new \InvalidArgumentException(sprintf("Key %s exist"), $step->getKey());
        }

        $this->steps[$step->getKey()] = $step;
    }

    /**
     * setCurrentKey 
     * Moves the internal iterator position to the key step.
     * 
     * @param string $key
     */
    public function setCurrentKey($key)
    {
        $this->steps[$key];
    }

    /**
     * current 
     * Gets the step of the collection at the current internal iterator position.
     *
     * @return Coo\SimpleWorkflowManager\Step\Step
     */
    public function current()
    {
        return current($this->steps);
    }

    /**
     * next 
     * Moves the internal iterator position to the next step.
     *
     * @return Coo\SimpleWorkflowManager\Step\Step
     */
    public function next()
    {
        return next($this->steps);
    }

    /**
     * prev 
     * Moves the internal iterator position to the prev step.
     *
     * @return Coo\SimpleWorkflowManager\Step\Step
     */
    public function prev()
    {
        return prev($this->steps);
    }

    /**
     * first
     * 
     * @return Coo\SimpleWorkflowManager\Step\Step
     */
    public function first()
    {
        return reset($this->steps);
    }

    /**
     * last
     * 
     * @return Coo\SimpleWorkflowManager\Step\Step
     */
    public function last()
    {
        return end($this->steps);
    }

    /**
     * containsKey
     * @param  string $key
     * @return boolean
     */
    public function containsKey($key)
    {
        return isset($this->steps[$key]);
    }

    /**
     * count 
     * Implementation of the Countable interface.
     *
     * @return integer The number of steps in the collection.
     */
    public function count()
    {
        return count($this->steps);
    }

    /**
     * remove
     * Removes an step with a specific key/index from the collection.
     *
     * @param mixed $key
     * @return mixed The removed step or NULL, if no step exists for the given key.
     */
    public function remove($key)
    {
        if (isset($this->steps[$key])) {
            $removed = $this->steps[$key];
            unset($this->steps[$key]);
            
            return $removed;
        }

        return null;
    }

    /**
     * offsetExists
     * ArrayAccess implementation of offsetExists()
     *
     * @see containsKey()
     */
    public function offsetExists($offset)
    {
        return $this->containsKey($offset);
    }

    /**
     * offsetGet
     * ArrayAccess implementation of offsetGet()
     *
     * @see get()
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * ArrayAccess implementation of offsetGet()
     *
     * @see add()
     * @see set()
     */
    public function offsetSet($offset, $value)
    {
        if (!isset($offset)) {
            return $this->add($value);
        }
        return $this->set($offset, $value);
    }

    /**
     * ArrayAccess implementation of offsetUnset()
     *
     * @see remove()
     */
    public function offsetUnset($offset)
    {
        return $this->remove($offset);
    }

    /**
     * isEmpty
     * @return boolean
     */
    public function isEmpty()
    {
        return (bool) !$this->steps;
    }

    /**
     * Returns a string representation of this object.
     *
     * @return string
     */
    public function __toString()
    {
        return __CLASS__ . '@' . spl_object_hash($this);
    }

    /**
     * Clears the collection.
     */
    public function clear()
    {
        $this->steps = array();
    }

    /**
     * __destruct delete all private attributes
     */
    public function __destruct()
    {
        foreach ($this as $key => $value) {
            unset($this->$key);
        }
    }
}
