<?php
namespace Controllers;

use \Controllers\AbstractControllerInterface;
use \Bots\BotFactory;
use \Services\RequestFactory;
use \Helpers\cURL;

class BotController extends AbstractControllerInterface
{
    /**
     * [$payloader description]
     * @var [type]
     */
    protected $payloader = [
        'name' => 'Facebook',
        'service' => '\\Services\\FacebookRequest',
        'bot' => [
            'class' => '\\Bots\\ReplicantBot',
            'command' => '\\Commands\\FacebookCommands',
            'AI' => [
                'regex' => '\\AI\\Models\\Replicant\\ReplicantRepl',
            ]
        ]
    ];
    /**
    * [__construct]
    * @param array $botConf Configurações do Bot
    */
    public function __construct() { }

    public function get()
    {
        $token = getenv('BOT_VERIFY_TOKEN');
        $this->subscribe($token);
        return ;
    }

    public function post()
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
     * Exemplo de requisição, vinda do Facebook
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
     *                   "text":"Hello World!"
     *                }
     *             }
     *          ]
     *       }
     *    ]
     * }
     */
    public function dispatch()
    {
        $request = $this->getArrayDataPayload();
        
        $payloaderRequest = RequestFactory::build($this->payloader, $request);

        if (isset($payloaderRequest->request['payload']['sender']['id'])) {
            $curl = new cURL();
            $url = 'https://graph.facebook.com/v2.6/me/messages?access_token='.getenv('BOT_TOKEN');
            $data = ['recipient' => ['id' => $payloaderRequest->request['payload']['sender']['id']], 'sender_action' => 'typing_on'];
            
            $response = $curl->call($url, $data, cURL::TYPE_POST);    
        }

        $botResolver = BotFactory::assemble($this->payloader, $payloaderRequest);
        
        $botClient = $botResolver->initCommands($this->payloader, $payloaderRequest, $botResolver);
        
        $botClient->executeBotCommand();
    }
}