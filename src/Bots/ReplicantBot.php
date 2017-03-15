<?php
namespace Bots;

use \Factory\AbstractRequest;
use \Factory\AbstractCommandFactory;
use \Helpers\Facebook\SimpleMessage;
use \Helpers\Facebook\StickerMessage;
use \Helpers\Facebook\ButtonMessage;
use \Helpers\Facebook\ImageMessage;
use \Helpers\Facebook\QuickReply;
use \Helpers\Facebook\GenericTemplate;
use \Helpers\Facebook\CallButton;
use \Services\FirebaseFacade as Firebase;
use \Commands\Botparser;

class ReplicantBot extends AbstractCommandFactory implements BotInterface
{
    /**
     * Id do usuário que está se comunicando com o bot
     * @var int
     */
    private $senderId;
    /**
     * Id do bot
     * @var int
     */
    private $recipientId;
    /**
    * Classe requisitante do bot
    * @var AbstractRequest
    */
    public $requesterPayload;
    /**
     * [$Firebase FirebaseFacade]
     * @var [FirebaseFacade]
     */
    public $Firebase;
    /**
     * [$Botparser Botparser]
     * @var [Botparser]
     */
    public $Botparser;

    /**
    * [__construct]
    * @param AbstractRequest $requesterPayload Requisitante
    */
    public function __construct(AbstractRequest $requesterPayload)
    {
        $token = getenv('BOT_TOKEN');
        $requesterPayload->setToken($token);

        $this->requesterPayload = $requesterPayload;
        $this->Firebase = new Firebase();
        $this->Botparser = new Botparser();

        if (is_null($this->requesterPayload->request['type'])) {
            return false;
        }
        
        $this->senderId = $this->requesterPayload->request['payload']['sender']['id'];
        $this->recipientId = $this->requesterPayload->request['payload']['recipient']['id'];

        $userProfileData = $this->requesterPayload->getUserProfileData($this->senderId);
        $this->Botparser->setUserProfileData($userProfileData);
    }
    /**
     * [getStarted description]
     * @return [type] [description]
     */
    public function getStarted()
    {
        $userProfileData = $this->requesterPayload->getUserProfileData($this->senderId);
        $this->Firebase->targetUser($this->senderId, $userProfileData);

        $botMessage = "@bot.randGreet @user.first_name @bot.salute";
        
        $this->Botparser->setBotMessage($botMessage);
        $initialGreet = $this->Botparser->execute($botMessage);
        $messageGreet = new SimpleMessage($initialGreet);
        $botMessageResponse = $messageGreet->getMessage();


        return $botMessageResponse;
    }

    /**
     * Resposta padrão, quando não entender um comando
     * @return string Texto caso o comando não tenha sido encontrado
     */
    public function commandNotFound()
    {
        $botMessage = "@user.first_name no momento estou preparado para demonstração." . PHP_EOL . PHP_EOL;

        $this->Botparser->setBotMessage($botMessage);
        $botMessage = $this->Botparser->execute($botMessage);

        $message = new SimpleMessage($botMessage);
        $botMessageResponse = $message->getMessage();

        return $botMessageResponse;
    }
    /**
     * [greet description]
     * @return [type] [description]
     */
    public function greet()
    {
        $botMessage = "@bot.randGreet @user.first_name @bot.salute" . PHP_EOL;
        $this->Botparser->setBotMessage($botMessage);
        $initialGreet = $this->Botparser->execute($botMessage);
        $messageGreet = new SimpleMessage($initialGreet);
        $botMessageResponse[] = $messageGreet->getMessage();

        $botMessage = "@bot.quickTip" . PHP_EOL . PHP_EOL;
        $this->Botparser->setBotMessage($botMessage);
        $initialGreet = $this->Botparser->execute($botMessage);
        $messageGreet = new SimpleMessage($initialGreet);
        $botMessageResponse[] = $messageGreet->getMessage();

        return $botMessageResponse;
    }
}
