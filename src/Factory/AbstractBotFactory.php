<?php
namespace Factory;

use \Factory\AbstractRequest;

abstract class AbstractBotFactory
{
	abstract public static function assemble(AbstractRequest $request, array $configs);
}
