<?php

namespace Coo\SimpleWorkflowManager\Step;

/**
 * StepTransformer
 * 
 * @author Florent Mondoloni
 */
class StepTransformer
{
    /**
     * transform Create step from config.
     * 
     * @param  string $key      
     * @param  array $properties
     * 
     * @return Coo\SimpleWorkflowManager\Stepper\Step
     */
    public static function transform($key, $properties)
    {
        $step = new Step();
        $step->setKey($key);

        foreach ($properties as $name => $value) {
            $step->addProperty($name, $value);
        }

        return $step;
    }
}
