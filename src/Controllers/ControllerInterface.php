<?php
namespace Controllers;

interface ControllerInterface
{
    public function __construct();

    public function handle();

    public function method();

    public function uri();
}
