<?php

namespace Factory;

abstract class AbstractRequestFactory
{
	abstract public static function build(array $requestPayloader, array $request);
}
