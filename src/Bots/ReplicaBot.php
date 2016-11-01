<?php
namespace Bots;

use \Factory\AbstractRequest;
use \Factory\AbstractCommandFactory;
use \AI\WordPattern;
use \Helpers\Facebook\SimpleMessage;
use \Helpers\Facebook\StickerMessage;
use \Helpers\Facebook\ButtonMessage;
use \Helpers\Facebook\ImageMessage;
use \Helpers\Facebook\QuickReply;
use \Helpers\Facebook\GenericTemplate;

class ReplicaBot extends AbstractCommandFactory implements BotInterface
{
    /**
    * Classe requisitante do bot
    * @var AbstractRequest
    */
    public $requesterPayload;
    /**
    * Dados a serem enviados
    * @var AbstractRequest
    */
    public $responseMessage;
    /**
     * Resposta padrão, quando não entender um comando
     * @var string
     */
    public $commandNotFound;
    /**
    * [__construct]
    * @param AbstractRequest $requesterPayload Requisitante
    * @param array           $botConfig        Configuração do bot
    */
    public function __construct(AbstractRequest $requesterPayload, array $botConfig)
    {
        $requesterPayload->setToken($botConfig['token']);

        $this->requesterPayload = $requesterPayload;
    }
    /**
     * Caso o comando não tenha sido encontrado
     * @return string Texto caso o comando não tenha sido encontrado
     */
    public function commandNotFound()
    {
        $this->commandNotFound = 'Eu não entendi o seu comando. Tente novamente, por favor.';
        return $this->commandNotFound;
    }

    public function meetGreet()
    {
        $recipientId = $this->requesterPayload->request['payload']['sender']['id'];
        $userProfileData = $this->requesterPayload->getUserProfileData($recipientId);

        $message = sprintf('%s, tudo bem?', $userProfileData['first_name']);
        $simpleMessage = new SimpleMessage($message);
        return $simpleMessage->getMessage();
    }

    public function goodBye()
    {
        $recipientId = $this->requesterPayload->request['payload']['sender']['id'];
        $userProfileData = $this->requesterPayload->getUserProfileData($recipientId);

        $message = 'Então vamos nos despedir :)' . PHP_EOL;
        $message .= sprintf('Tchau, %s, até a próxima.', $userProfileData['first_name']);
        $simpleMessage = new SimpleMessage($message);

        return $simpleMessage->getMessage();
    }
}
