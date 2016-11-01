<?php
namespace Controllers;

interface ControllerInterface
{
    public function __construct(array $botConfigs);

    public function dispatch();
}
