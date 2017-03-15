<?php
namespace Factory;

abstract class AbstractCommand
{
    abstract public static function initCommands($payloader, AbstractRequest $request, $superClassBot);

    abstract public function commandNotFound();
}
