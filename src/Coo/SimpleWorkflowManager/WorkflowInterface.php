<?php

namespace Coo\SimpleWorkflowManager;

/**
 * 
 */
interface WorkflowInterface
{
    public function getModelStatus();
    public function setModel($model);
}
