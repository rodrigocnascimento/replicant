<?php
namespace Commands;

use \Factory\AbstractRequest;
use \Bots\BotInterface;

abstract class CommandInterface
{
    abstract public function __construct();

    abstract public function setUserData($userProfileData);

    abstract public function sendMessage();

    abstract public function setResponseMessage($userInput);

    abstract public function setLazyLoad(AbstractRequest $requesterPayload, BotInterface $superClassBot);
}
