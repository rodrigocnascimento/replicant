<?php
namespace Factory;

abstract class AbstractRequest
{
    abstract public function __construct(array $userInput);

    abstract public function setToken(string $token);

    abstract public function getRequest();

    abstract public function setRequest($payload, $type);

    abstract public function setMessagePayload($payload);
}
