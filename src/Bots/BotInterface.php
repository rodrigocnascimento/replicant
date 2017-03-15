<?php
namespace Bots;

use \Factory\AbstractRequest;

interface BotInterface
{
  public function __construct(AbstractRequest $request);
}
