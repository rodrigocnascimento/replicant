<?php
namespace Commands;

use \Factory\AbstractRequest;
use \Bots\BotInterface;

abstract class CommandInterface
{
    abstract public function __construct();

    abstract public function setLazyLoad(AbstractRequest $requesterPayload, BotInterface $superClassBot);

    abstract public function executeBotCommand();

    abstract public function executePayloaderCommand($userInput);
}
