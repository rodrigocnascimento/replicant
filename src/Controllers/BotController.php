<?php
namespace Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\Container;
use \Controllers\ControllerInterface;
use \Bots\BotFactory;
use \Services\RequestFactory;

class BotController implements ControllerInterface
{
    /**
     * Configurações do bot
     * @var array
     */
    public $botConf;

    /**
    * [__construct]
    * @param array $botConf Configurações do Bot
    */
    public function __construct(array $botConf)
    {
        $this->botConf = $botConf;
    }

    /**
    * Recebe as requisições vindas da plataforma que o Bot está conectado
    */
    public function dispatch()
    {
        $request = json_decode(file_get_contents('php://input'), true);
        // @todo determinar o payloader através de uma abstractfactory?
        $requestPayloader = [
            'name' => 'Facebook',
            'className' => '\\Services\\FacebookRequest'
        ];

        $requesterPayload = RequestFactory::build($requestPayloader, $request);

        $botResolver = BotFactory::assemble($requesterPayload, $this->botConf);

        $botClient = $botResolver->initCommands($requesterPayload, $botResolver);

        $botClient->sendMessage();
    }
}
