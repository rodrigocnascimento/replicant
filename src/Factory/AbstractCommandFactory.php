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
	public static function initCommands(AbstractRequest $request, $superClassBot) {

		$stringClass = sprintf("\\Commands\\%sCommands", $request->request['payloader']);

		if (class_exists($stringClass)) {
			$commandClass = new $stringClass();

			$commandClass->setLazyLoad($request, $superClassBot);

			return $commandClass;
		}

		throw new \Exception('Class not found');
	}

	public function commandNotFound() {}
}
