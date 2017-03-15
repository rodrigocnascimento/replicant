<?php
namespace Commands;

use \Helpers\Emoji;
/**
 * ((\@([\w]{1,})\.)([\w]{1,})([\w\(\,\)]{1,}){0,})
 * $botFunction = sprintf("There are many variations of  @calculate.sum(1,2,3) of  alteration in some form, by injected humour, words g  @user.first_name use a @sync.userAnything(%s) Ipsum generators on the Internet tend to repeat predefined chunks as necessary on @greetings.timegreet Internet.", $senderId);
 *
 * $this->Botparser->setUserProfileData($userProfileData);
 * $initialGreet = $this->Botparser->execute($botFunction);
 * $messageGreet = new SimpleMessage($initialGreet);
 * $botMessage[] = $messageGreet->getMessage();
 */
class Botparser
{

    public $regexp = '/(\@([\w]+)\.)([\w]+)/';

    public $userProfileData;

    public $botMessage;

    public function setUserProfileData($userProfileData)
    {
        $this->userProfileData = $userProfileData;
    }

    public function setBotMessage($botMessage)
    {
        $this->botMessage = $botMessage;
    }    

    public function execute()
    {
        preg_match_all($this->regexp, $this->botMessage, $matches);

        array_walk($matches[0], function($pattern) {
            $pattern = str_replace(',', '', $pattern);
            list($main, $execute) = explode('.', $pattern);
            $this->matchedPattern = $pattern;

            switch ($main) {
                case strpos($main, '@user'):
                    $this->botMessage = $this->userDataReplace();
                    break;
                case strpos($main, '@bot'):
                    $this->botMessage = $this->{$execute}();
                    break;
                case strpos($main, '@emoji'):
                    $this->botMessage = $this->emoji();
                    break;    
            }
        });

        return $this->botMessage;
    }

    public function quickTip() 
    {
        $quickTip = [
            "Por enquanto respondo a simples comandos :D"
        ];
        $quickTip = $quickTip[count($quickTip) - 1];
        return $this->replace($quickTip);
    }

    public function randBye($upperCase = true)
    {
        $bye = ['até mais', 'xau', 'tchau'];
        $bye = $bye[count($bye) - 1];
        if ($upperCase) {
            $bye = ucfirst($bye);
        }
        return $this->replace($bye);
    }

    public function randGreet($upperCase = true)
    {   

        $greet = ['olá', 'oi'];
        $greet = $greet[count($greet) - 1];
        if ($upperCase) {
            $greet = ucfirst($greet);
        }
        return $this->replace($greet);
    }

    public function salute()
    {

        $hour = date('H');
        if ($hour >= '7' && $hour <= '12') {
            $salute[] = 'bom dia';
            $salute[] = 'que dia lindo';
        }

        if ($hour >= '12') {
            $salute[] = 'boa tarde';
            $salute[] = 'que tarde linda';
        }

        if($hour >= '18') {
            $salute[] = 'boa noite';
            $salute[] = 'que noite linda';
        }

        if($hour >= '23') {
            $salute[] = 'acordado até essa hora';
        }
        $saluteTxt = $salute[count($salute) - 1];
        return $this->replace($saluteTxt);
    }

    public function emoji()
    {
        $emoji = new Emoji();
        list(, $emojiName) = explode('.', $this->matchedPattern);
        $emojiIcon = $emoji->getEmoji($emojiName);
        return $this->replace($emojiIcon);
    }

    public function userDataReplace()
    {
        list(, $field) = explode('.', $this->matchedPattern);
        return $this->replace($this->userProfileData[$field]);
    }

    private function replace($input)
    {
        return str_replace($this->matchedPattern, $input, $this->botMessage);
    }
}
