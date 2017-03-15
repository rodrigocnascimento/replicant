<?php
namespace Factory;

class AbstractCommandFactory extends AbstractCommand
{
	/**
	 * Inicializa os comandos do Bot
	 * @param  AbstractRequest $request       Requisição do payloader
	 * @param  BotInterface     $superClassBot Bot que está sendo executado
	 * @return CommandInterface Comandos do payloader
	 */
	public static function initCommands($payloader, AbstractRequest $request, $superClassBot) 
	{
		if (class_exists($payloader['bot']['command'])) {
			$commandClass = new $payloader['bot']['command']();

			$commandClass->setLazyLoad($request, $superClassBot);

			return $commandClass;
		}

		throw new \Exception("Class not found");
	}

	public function commandNotFound() {}
}
