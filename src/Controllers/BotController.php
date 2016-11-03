<?php
namespace Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Slim\Container;
use \Controllers\AbstractControllerInterface;
use \Bots\BotFactory;
use \Services\RequestFactory;

class BotController extends AbstractControllerInterface
{
    protected $botConfigs;

    // @todo determinar o payloader através de uma abstractfactory?
    protected $payloader = [
        'name' => 'Facebook',
        'className' => '\\Services\\FacebookRequest'
    ];
    /**
    * [__construct]
    * @param array $botConf Configurações do Bot
    */
    public function __construct(array $botConfigs)
    {
        $this->botConfigs = $botConfigs;
    }

    public function handleWebhookGet()
    {
        $token = $this->botConfigs[$this->payloader['name']]['hub_verify_token'];
        $this->subscribe($token);
        return ;
    }

    public function handleWebhookPost()
    {
        $this->dispatch();
        return ;
    }
    /**
    * Exemplo de Requisição enviada para subscribe da page
    *
    * GET /index.php?hub.mode=subscribe&hub.challenge=2127260776&hub.verify_token=my_special_token HTTP/1.1
    * User-Agent: facebookplatform/1.0 (+http://developers.facebook.com)
    * Host: *.host.*
    * Accept: *\/*
    * Accept-Encoding: deflate, gzip
    * X-Forwarded-Proto: https
    * X-Forwarded-For: XX.XX.XXX.XXX
    *
    */
    private function subscribe(string $verifyToken)
    {
        $facebookRequestMode    = filter_input(INPUT_GET, 'hub_mode');
        $facebookToken          = filter_input(INPUT_GET, 'hub_verify_token');
        $facebookChallenge      = filter_input(INPUT_GET, 'hub_challenge');

        $isSubscribe = ($facebookRequestMode === 'subscribe');
        $isTokenValid = ($facebookToken === $verifyToken);

        $validInscription = $isSubscribe && $isTokenValid;

        if ($validInscription) {
            echo $facebookChallenge;
        }
        return ;
    }


    /**
     * Exemplo de requisição comum, vinda do Facebook
     *
     * POST /index.php HTTP/1.1
     * Host: *.host.*
     * Accept: *\/*
     * Accept-Encoding: deflate, gzip
     * Content-Type: application/json
     * X-Hub-Signature: sha1=b2774508eab369be26ff8bf3a61e2a2ed3c7192c
     * Content-Length: 259
     * X-Forwarded-Proto: https
     * X-Forwarded-For: XXX.XXX.XX.XX
     *
     *  {
     *   "object":"page",
     *   "entry":[
     *       {
     *          "id":"PAGE_SCOPE_ID",
     *          "time":1478004964471,
     *          "messaging":[
     *             {
     *                "sender":{
     *                   "id":"USER_CHAT_SCOPE_ID"
     *                },
     *                "recipient":{
     *                   "id":"PAGE_SCOPE_ID"
     *                },
     *                "timestamp":1478004964352,
     *                "message":{
     *                   "mid":"mid.1478004964352:4be33d7e10",
     *                   "seq":639,
     *                   "text":"oi"
     *                }
     *             }
     *          ]
     *       }
     *    ]
     * }
     */
    public function dispatch()
    {
        $request = json_decode(file_get_contents('php://input'), true);

        $requesterPayload = RequestFactory::build($this->payloader, $request);

        $botResolver = BotFactory::assemble($requesterPayload, $this->botConfigs);

        $botClient = $botResolver->initCommands($requesterPayload, $botResolver);

        $botClient->sendMessage();
    }
}
