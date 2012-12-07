# SimpleWorkflow

PHP library for easy workflow management.

## Requirements

* PHP 5.3+

## Installation

### Add to your project Composer packages

Just add `coo/simple-workflow` package to the requirements of your Composer JSON configuration file,
and run `php composer.phar install` to install it.

### Install from GitHub

Clone this library from Git with `git clone https://github.com/Coodit/SimpleWorkflow.git`.

Goto to the library directory, get Composer phar package and install vendors:

```
curl -s https://getcomposer.org/installer | php
php composer.phar install
```

You're ready to go.

## Configuration

First, you must create your first workflow. Here is an example:


``` php
<?php

namespace You\Project\Workflow;

use Coo\SimpleWorkflowManager\BaseWorkflow;
use Coo\SimpleWorkflowManager\WorkflowInterface;

class ExampleWorkflow extends BaseWorkflow implements WorkflowInterface
{
    public function executeValid()
    {
        /**
         * Your logic.
         * You can use $this->getModel() to access your model
         * at any time.
         */
        if ($this->getModel()->hasFooBarOption()) {
            $this->executePending();
        }
    }

    public function executePending()
    {
        // Your logic.
    }

    public function executeDuring()
    {
        // Your logic.
    }

    public function executeComplete()
    {
        // Your logic.
    }

    /**
     * You must have a getModelStatus method to use
     * actual model status at any time.
     */
    public function getModelStatus()
    {
        return $this->model->getStatus();
    }
}
```

## Usage

``` php
<?php

require_once '/path/to/your/vendor/autoload.php';

use Coo\SimpleWorkflowManager\Manager\WorkflowFactory;

/**
 * Use your workflow, created before, and your concerned entity.
 */
use You\Project\Workflow\ExampleWorkFlow;
use You\Project\Entity\ExampleEntity;

/**
 * Declare your workflow steps, with facultative specific options/parameters.
 */
$orderConfig = array(
        'pending'  => array(),
        'during'   => array('icon' => 'icon-during.png'),
        'valid'    => array('icon' => 'icon-valid.png'),
        'complete' => array(
            'icon'            => 'icon-complete.png',
            'other-parameter' => 'param'
        ),
 );

/**
 * Use default factory or create yours, based on it, or
 * call the manager from your DiC container.
 */
$workflowManager = WorkflowFactory::create($orderConfig);

/**
 * Instanciate your specific worflow.
 */
$exampleWorkflow = new ExampleWorkFlow();
$exampleWorkflow->setModel(new ExampleEntity());

$workflowManager->setModelWorkflow($exampleWorkflow);

/**
 * Steps calls examples.
 * 
 * $workflowManager->executeCurrent();
 * $workflowManager->executeNext();
 * $workflowManager->executePrevious();
 * $workflowManager->executeFirst();
 * $workflowManager->executeLast();
 * $workflowManager->executeToEnd();
 *
 * You can jump to a specific step.
 * Use execute() to go to a step and executeTo() for
 * passing through each transitive ones.
 * Theses methods must have your step key as argument
 * ('pending', 'during', etc.).
 * 
 * $workflowManager->execute('stepKey');
 * $workflowManager->executeTo('stepKey')
 *
 * Here is an example:
 */
if ($workflowManager->executeNext()) {
   // Success.
} else {
   // Fail.
}
```
Here is an example to use step collection.

```shell
<ul>
    <?php foreach ($workflowManager->getStepCollection() as $step): ?>
        <li class="<?php echo $step->isCurrent() ? 'current': '' ?>">

            <?php if ($step->hasProperty('icon')): ?>
                <span class="icon <?php echo $step->getProperty('icon') ?>"></span>
            <?php endif; ?>

            <?php echo $step ?>
        </li>
    <?php endforeach ?>
</ul>
```