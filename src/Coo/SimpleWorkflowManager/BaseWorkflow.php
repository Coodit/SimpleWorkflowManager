<?php

namespace Coo\SimpleWorkflowManager;

/**
* Manager class.
*
* @author Florent Mondoloni
*/
abstract class BaseWorkflow
{
    protected $model;

    /**
     * setModel
     * @param mixed $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * getModel
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }
}
