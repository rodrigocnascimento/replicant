<?php
namespace Bots;

use \Factory\AbstractRequest;
use \Factory\AbstractBotFactory;

class BotFactory extends AbstractBotFactory
{
    /**
    * Invoca o bot apropriado de acordo com a requisição
    * @param  AbstractRequest $request requisição abstraída
    * @param  array           $configs configurações inicias do bot
    * @return BotInterface    Bot abstraído
    */
    public static function assemble($payloader, AbstractRequest $request)
    {
        if (class_exists($payloader['bot']['class'])) {
            $botClass = new $payloader['bot']['class']($request);
            $botClass->payloader = $payloader;
        }

        return $botClass;
    }
}
