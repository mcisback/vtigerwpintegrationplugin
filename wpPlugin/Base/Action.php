<?php

namespace Mcisback\WpPlugin\Base;

abstract class Action {
    protected ?string $name = null;
    public bool $isAjax;
    public bool $useClassNameAsActionName;

    /**
     * TODO: $extraArgs
     */
    public function __construct(
        string $name, array $extraArgs = []
    ) {
        $this->isAjax = false;
        $this->useClassNameAsActionName = true;
        $this->extraArgs = $extraArgs;

        return $this->setName($name);
    }

    public function getName() {
        return $this->name;
    }
    
    public function getClassName() {
        return end(
            explode('\\', $this->name)
        );
    }

    public function setName(string $name) {
        $this->name = $name;

        return $this;
    }

    public function beforeRun(...$args) {
        $this->run(...$args );
    }

    public function getRunFunctionName() {
        return 'beforeRun';
    }

    public abstract function run(...$args);
}