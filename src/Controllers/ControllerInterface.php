<?php
namespace Controllers;

interface ControllerInterface
{
    public function __construct(array $botConfigs);

    public function handle();

    public function dispatch();

    public function method();

    public function uri();
}
