<?php

namespace Coo\SimpleWorkflowManager\Manager;

use Coo\SimpleWorkflowManager\Step\StepTransformer;
use Coo\SimpleWorkflowManager\Step\StepCollection;

/**
 * WorkflowFactory.
 *
 * @author Florent Mondoloni
 */
class WorkflowFactory
{
    public static function create($config)
    {
        $collection = new StepCollection();
        
        foreach ($config as $key => $properties) {
            $step = StepTransformer::transform($key, $properties);
            $collection->add($step);
        }

        return new Manager($collection);
    }
}
