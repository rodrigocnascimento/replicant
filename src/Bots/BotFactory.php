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
    public static function assemble(AbstractRequest $request, array $configs)
    {
        $botConfig = $configs[$request->request['payloader']]['Bots'][$request->request['botId']];

        if (class_exists($botConfig['className'])) {
            $botClass = new $botConfig['className']($request, $botConfig);
        }

        return $botClass;
    }
}
