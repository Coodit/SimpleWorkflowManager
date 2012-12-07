<?php

namespace Coo\SimpleWorkflowManager\Manager;

use Coo\SimpleWorkflowManager\Step\StepCollection;
use Coo\SimpleWorkflowManager\WorkflowInterface;
use Coo\SimpleWorkflowManager\Exception\InvalidWorkflowStepException;

/**
 * Manager class.
 *
 * @author Florent Mondoloni
 */
class Manager
{
    private $stepper;
    private $modelWorkflow;

    /**
     * __construct
     * @param Coo\SimpleWorkflowManager\Step\StepCollection $stepCollection
     */
    public function __construct(StepCollection $stepCollection)
    {
        $this->stepCollection = $stepCollection;
    }

    /**
     * setModelWorkflow
     * @param WorkflowInterface $modelWorkflow
     */
    public function setModelWorkflow(WorkflowInterface $modelWorkflow)
    {
        $this->modelWorkflow = $modelWorkflow;

        $this->keyCheck($this->modelWorkflow->getModelStatus());
        $this->stepCollection->setCurrentKey($this->modelWorkflow->getModelStatus());
    }

    /**
     * execute
     * @param  string $status
     */
    public function execute($status)
    {
        $this->keyCheck($status);

        $method = self::camelize('execute_'.$status);
        $this->modelWorkflow->$method();
    }

    /**
     * keyCheck
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    private function keyCheck($key)
    {
        if (!$this->stepCollection->containsKey($key)) {
            throw new InvalidWorkflowStepException(sprintf("Key %s does not exist", $key));
        }
    }

    /**
     * executeCurrent
     */
    public function executeCurrent()
    {
        return $this->executeMethod('current');
    }

    /**
     * executeNext
     */
    public function executeNext()
    {
        return $this->executeMethod('next');
    }

    /**
     * executePrevious
     */
    public function executePrevious()
    {
        return $this->executeMethod('prev');
    }

    /**
     * executeFirst
     */
    public function executeFirst()
    {
        return $this->executeMethod('first');
    }

    /**
     * executeLast
     */
    public function executeLast()
    {
        return $this->executeMethod('last');
    }

    /**
     * executeTo
     * @param  string $status step how far you want to go.
     * 
     * @return mixed
     */
    public function executeTo($status)
    {
        $this->keyCheck($status);
        $found = false;

        while (false === $found) {
            if ($step = $this->executeNext()) {
                if ($step->getKey() == $status) {
                    $found = true;
                }
            } else {
                $found = true;
            }
        }

        return $step;
    }

    /**
     * executeToEnd
     * @param  string $status step how far you want to go.
     * 
     * @return mixed
     */
    public function executeToEnd()
    {
        $isNotLast = true;

        while (true === $isNotLast) {
            if (!$step = $this->executeNext()) {
                $isNotLast = false;
            }
        }

        return $step;
    }

    /**
     * executeMethod
     * 
     * @param  string $method
     * @return mixed
     */
    public function executeMethod($method)
    {
        if (!in_array($method, $this->methodSupport())) {
            $text = "Method %s does not support. The supported parameters are %s";
            $message = sprintf($text, $method, implode(', ', $this->methodSupport()));
            throw new \InvalidArgumentException($message);
        }

        $step = $this->stepCollection->$method();
        if ($step) {
            $this->stepCollection->updateCurrent($step);
            $this->execute($step->getKey());
            return $step;
        }

        return false;
    }

    /**
     * methodSupport
     * 
     * @return array
     */
    protected function methodSupport()
    {
        return array(
            'current',
            'next',
            'prev',
            'first',
            'last'
        );
    }

    /**
     * getStepCollection
     * 
     * @return Coo\SimpleWorkflowManager\Stepper\StepCollection
     */
    public function getStepCollection()
    {
        return $this->stepCollection;
    }

    /**
     * camelize
     * @param  string $value
     * @return string
     */
    public static function camelize($value)
    {
        $value = preg_replace("/([_-\s]?([a-z0-9]+))/e", "ucwords('\\2')", $value);

        return lcfirst($value);
    }
}
