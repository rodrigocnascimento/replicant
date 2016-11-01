<?php
namespace Commands;

use \Commands\CommandInterface;
use \Bots\BotInterface;
use \Factory\AbstractRequest;
use \AI\WordPattern;
use \Helpers\Facebook\SimpleMessage as SimpleMessage;
use \Helpers\Facebook\StickerMessage as StickerMessage;
use \Helpers\Facebook\ButtonMessage as ButtonMessage;
use \Helpers\Facebook\ImageMessage as ImageMessage;
use \Helpers\Facebook\QuickReply as QuickReply;
use \Helpers\Facebook\GenericTemplate as GenericTemplate;

class FacebookCommands extends CommandInterface
{
    /**
    * Classe requisitante do bot
    * @var AbstractRequest
    */
    public $requesterPayload;
    /**
     * Bot que está executando os comandos
     * @var [type]
     */
    public $superClassBot;
    /**
     * Dados do usuário que está interagindo com o bot
     * @var [type]
     */
    protected $userProfileData = [];

    public function __construct() {}

    /**
    * Atribui os dados ao usuário que interage co o Bot
    * @param [type] $userProfileData [description]
    */
    public function setUserData($userProfileData)
    {
        $this->userProfileData = $userProfileData;
    }

    /**
    * Envia a mensagem para o usuário que está interagindo com o bot
    * @return
    */
    public function sendMessage()
    {
        $userInput = $this->requesterPayload->getRequest();

        if (isset($userInput['payload']) && !is_null($userInput['type'])) {

            $recipientId = $userInput['payload']['sender']['id'];

            $this->responseMessage = $this->setResponseMessage($userInput);

            $this->requesterPayload->sendMessage($this->responseMessage, $recipientId);
        }
    }

    /**
     * Atribui a mensagem enviada ao bot
     * com o tipo de mensagem envada pelo usuário
     * @param array $userInput Entrada do usuário
     */
    public function setResponseMessage($userInput)
    {

        if (!method_exists($this, $userInput['type'])) {
            $message = sprintf('Eu não entendi o comando!', $this->userProfileData['first_name']);
            $simpleMessage = new SimpleMessage($message);
            return $simpleMessage->getMessage();
        }

        return $this->{$userInput['type']}($userInput);
    }

    /**
     * Atribui as propiedades
     * @param AbstractRequest $requesterPayload Request do payloader
     * @param BotInterface    $superClassBot    Bot que está executando os comandos
     */
    public function setLazyLoad(AbstractRequest $requesterPayload, BotInterface $superClassBot)
    {
        $this->requesterPayload = $requesterPayload;
        $this->superClassBot = $superClassBot;
    }

    /**
    * Executa um comando
    * @param  string $command Nome do comando a ser executado
    * @param  [type] $args   Argumentos do comando
    * @return BotInterface::commands Retorna os comandos a serm execurados ou nada
    */
    public function execute($command, $args = null)
    {
        if (method_exists($this->superClassBot, $command)) {
            return $this->superClassBot->{$command}($args);
        }
        return false;
    }

    /**
    * Retorno de mensagem
    * @param  [type] $userInput [description]
    * @return [type]            [description]
    */
    public function message($userInput)
    {
        /**
        * Envia os dados para serem analisados para determinar o que fazer
        * - PLN
        * - Machine Learning
        * - Deep Learning
        */
        $wordPattern = new WordPattern();
        $userIntent = $wordPattern->setUserIntent($userInput['payload']['message']['text']);

        $result = $this->execute($userIntent, $userInput);

        if (!$result) {
            $message = $this->superClassBot->commandNotFound();
            $simpleMessage = new SimpleMessage($message);

            return $simpleMessage->getMessage();
        }
        return $result;
    }

    /**
    * Retorno postback
    * @param  [type] $userInput [description]
    * @return [type]            [description]
    */
    public function postback($userInput)
    {
        $method = explode(':', $userInput['payload']['postback']['payload']);

        $responseMessage = $this->execute($method[0], $userInput);

        return $responseMessage;
    }
    /**
    * Retorno do quick_reply
    * @param  [type] $userInput [description]
    * @return [type]            [description]
    */
    public function quick_reply($userInput)
    {
        $method = $userInput['payload']['message']['quick_reply']['payload'];

        $responseMessage = $this->execute($method, $userInput);
        return $responseMessage;
    }
}
