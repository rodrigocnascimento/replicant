<?php
namespace Commands;

use \Commands\CommandInterface;
use \Bots\BotInterface;
use \Factory\AbstractRequest;
use \AI\Models\RePL;
use \Services\FirebaseFacade as Firebase;

class FacebookCommands extends CommandInterface
{
    /**
    * Classe requisitante do bot
    * @var AbstractRequest
    */
    public $requesterPayload;
    /**
     * Bot que está executando os comandos
     * @var BotInterface
     */
    public $superClassBot;
    /**
     * Dados do usuário que está interagindo com o bot
     * @var [type]
     */
    protected $userProfileData = [];
    /**
     * $Firebase FirebaseFacade
     * @var FirebaseFacade
     */
    public $firebase;
    /**
     * [__construct description]
     */
    public function __construct() 
    {
        $this->firebase = new Firebase();
    }

    /**
    * Envia a mensagem para o usuário que está interagindo com o bot
    * @return
    */
    public function executeBotCommand()
    {
        $userInput = $this->requesterPayload->getRequest();

        if (isset($userInput['payload']) && !is_null($userInput['type'])) {

            $recipientId = $userInput['payload']['sender']['id'];

            if (!method_exists($this, $userInput['type'])) {
                throw new \Exception("Método da Classe não existe");
            }
            /**
             * Executa o método requisitado pelo payloader
             * @var [type]
             */
            $this->responseMessage = $this->{$userInput['type']}($userInput);

            $this->requesterPayload->sendMessage($this->responseMessage, $recipientId);
        }
    }

    public function executePayloaderCommand($userPayload)
    {
        list($method) = explode(':', $userPayload);

        return $this->execute($method, $userPayload);
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
    * Executa o comando do bot, requisitado pela interface
    * @param  string $command Nome do comando a ser executado
    * @param  [type] $args   Argumentos do comando
    * @return BotInterface::commands Retorna o comando executado
    */
    public function execute($command, $args = null)
    {
        if (method_exists($this->superClassBot, $command)) {
            return $this->superClassBot->{$command}($args);
        }
        return false;
    }

    /**
    * Retorno de mensagem do Facebook
    * @param  [type] $userInput [description]
    * @return array $resuklt
    */
    public function message($userInput)
    {
        /**
        * Envia os dados para serem analisados para determinar a ação
        * - RePL
        *     - Solução própria baseada em regex
        * - NLP
        *     - Api.ai
        * - Machine Learning
        * - Deep Learning
        */
        $replClass = $this->superClassBot->payloader['bot']['AI']['regex'];
        if (!class_exists($replClass)) {
            throw new \Exception("Class does not exist");
        }

        $repl = new $replClass();
        $repl->setUserIntent($userInput['payload']['message']['text']);
        /**
         * Intenções do usuário
         * @var [array]
         */
        $userIntent = $repl->getUserIntent();
        list($userIntent) = $userIntent;

        /**
         * Aqui, tenho que mudar para o State Pattern, 
         * mas por enquanto fica somente assim mesmo
         *
         * Verifica se o usuário está em algum estado específico
         * @var array
         */
        $senderId = $userInput['payload']['sender']['id'];
        $userPath = sprintf('/users/%s/state', $senderId);
        $userState = $this->firebase->_toArray($this->firebase->get($userPath));

        if (!is_null($userState)) {
            if ($userIntent === 'cancelState') {
                return $this->superClassBot->cancelState($userState);
            }
            return $this->superClassBot->state($userInput, $userState);
        }

        $result = $this->execute($userIntent, $userInput);

        if (!$result) {
            return $this->superClassBot->commandNotFound();
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
        $userPayload = $userInput['payload']['postback']['payload'];

        $responseMessage = $this->executePayloaderCommand($userPayload);

        return $responseMessage;
    }
    /**
    * Retorno do quick_reply
    * @param  [type] $userInput [description]
    * @return [type]            [description]
    */
    public function quick_reply($userInput)
    {
        $userPayload = $userInput['payload']['message']['quick_reply']['payload'];

        $responseMessage = $this->executePayloaderCommand($userPayload);
        
        return $responseMessage;
    }
}
