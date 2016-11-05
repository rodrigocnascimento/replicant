<?php
namespace Services;

use \Factory\AbstractRequest;
use \Helpers\cURL;

/**
* Classe que recebe a requisição do Facebook
*/
class FacebookRequest extends AbstractRequest
{
    /**
    * URL da API
    * @var string
    */
    public $apiUrl = 'https://graph.facebook.com/v2.6';
    /**
    * URI da api de mensagem do FB
    * @var string
    */
    public $messageUriApi = '%s/me/messages';
    /**
    * Objeto Helper\cURL
    * @var Helper\cURL
    */
    public $curl = null;
    /**
    * Propriedades da requisição recebida
    * @var [type]
    */
    public $request = [
        'payloader' => 'Facebook',
        'botId' => null,
        'type' => null
    ];
    /**
    * Dados da requisição enviada pelo bot
    * @var array
    */
    public $botRequest = [];

    public $analytics;

    /**
    * [__construct description]
    * @param array $userInput Entrada http raw
    */
    public function __construct(array $userInput)
    {

        $this->curl = new cURL();

        if (empty($userInput)) {
            throw new \Exception("Empty Request");
        }

        $this->analytics = new BotAnalytics();

        $this->analytics->trackUser($userInput);

        $this->setBotId($userInput['entry'][0]['id']);

        $this->setMessagePayload($userInput['entry'][0]['messaging']);
    }

    /**
    * Atribui o token a requisição a ser enviada
    * @param string $token access_token do Facebook
    */
    public function setToken(string $token)
    {
        $this->botRequest['access_token'] = $token;
    }

    /**
    * Atribui o id do bot que está sendo chamado
    * @param string $botId Id do bot
    */
    public function setBotId($botId)
    {
        $this->request['botId'] = $botId;
    }

    /**
    * Atribui o request
    * @param array $payload Valor enviado pelo FacebookRequest
    * @param string $type    Tipo da mensagem enviada
    */
    public function setRequest($payload, $type)
    {
        $this->request['payload'] = $payload;
        $this->request['type'] = $type;
    }

    /**
    * Retorna o FacebookRequest
    * @return array O payload enviado pelo Facebook
    */
    public function getRequest()
    {
        return $this->request;
    }

    /**
    * Retorna o a requisição do bot
    * @return array Requisição a ser enviada
    */
    public function getBotRequest()
    {
        return $this->botRequest;
    }

    /**
    * Determina o tipo da mensagem que foi enviada
    * @param array $payload Request enviado pelo Facebook
    */
    public function setMessagePayload($payload)
    {
        array_filter($payload, function($payload, $key) {
            if ($this->isPostback($payload)) {
                $this->setRequest($payload, 'postback');
                return;
            }

            if ($this->isMessage($payload)) {
                $this->setRequest($payload, 'message');
                return;
            }

            if ($this->isQuickReply($payload)) {
                $this->setRequest($payload, 'quick_reply');
                return;
            }
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
    * Se a mensagem é apenas um echo do facebook
    * @param  array  $payload
    * @return boolean          [description]
    */
    public function isEcho($payload)
    {
        return isset($payload['message']['is_echo']);
    }

    /**
    * Se é uma mensagem
    * Para ser uma mensagem real de um usuário, não pode ser dos tipos:
    * - echo
    * - quick reply
    * - delivery
    * - read
    * @param  array  $payload
    * @return boolean é uma mensagem?
    */
    public function isMessage($payload)
    {
        return isset($payload['message'])
        && !$this->isQuickReply($payload)
        && !$this->isEcho($payload)
        && !$this->isRead($payload)
        && !$this->isDelivery($payload);
    }

    /**
    * Se a mensagem do tipo quick reply
    * @param  array  $payload
    * @return boolean é quick reply?
    */
    public function isQuickReply($payload)
    {
        return isset($payload['message']['quick_reply'])
        && !$this->isQuickReplyEcho($payload);
    }

    /**
    * Se é um postback
    * @param  array  $payload
    * @return boolean é um postback?
    */
    public function isPostback($payload)
    {
        return isset($payload['postback']);
    }

    /**
    * Se a mensagem é apenas um echo de um quick reply
    * @param  array  $payload
    * @return boolean é um echo de quick replay?
    */
    public function isQuickReplyEcho($payload)
    {
        return isset($payload['message']['is_echo'])
        && is_null($payload['message']['quick_reply']['payload']);
    }

    /**
    * Se a mensagem foi lida pelo usuário
    * @param  [type]  $payload [description]
    * @return boolean          [description]
    */
    public function isRead($payload)
    {
        return isset($payload['read']);
    }

    /**
    * Se a mensagem foi lida pelo usuário
    * @param  [type]  $payload [description]
    * @return boolean          [description]
    */
    public function isDelivery($payload)
    {
        return isset($payload['delivery']);
    }

    /**
    * Busca os dados do usuário que está falando com o BOT
    * @return array Dados do usuário
    */
    public function getUserProfileData($recipientId = null)
    {
        $recipientId = $recipientId ?? $this->request['recipient']['id'];
        $url = sprintf('%s/%s', $this->apiUrl, $recipientId);
        $request['access_token'] = $this->botRequest['access_token'];
        $request['recipient']['id'] = $recipientId;
        $request['fields'] = 'first_name,last_name,profile_pic,locale,timezone,gender';

        return $this->curl->call($url, $request, cURL::TYPE_GET);
    }

    /**
    * Envia a mensagem para o destinatário
    */
    public function sendMessage(array $messagePayload, string $recipientId)
    {
        $this->botRequest['recipient']['id'] = $recipientId;

        if (count($messagePayload) >= 1) {
            $messagePayload[0] = $messagePayload;
            unset($messagePayload['message']);
        }

        foreach ($messagePayload as $key => $payload) {
            $request = $this->botRequest + $payload;

            $response = $this->curl->call(sprintf($this->messageUriApi, $this->apiUrl), $request, cURL::TYPE_POST);
            $this->analytics->trackBot($recipientId, $payload, $response);
        }
    }
}
