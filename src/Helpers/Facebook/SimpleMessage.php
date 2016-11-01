<?php
namespace Helpers\Facebook;

class SimpleMessage
{

  protected $struct = ['message' => ['text' => '']];
  protected $notificationType = "REGULAR";

  public function __construct($textMessage, $notificationType = 'REGULAR')
  {

    $this->setMessage($textMessage);
  }

  private function truncate($str, $len)
  {
    $tail = max(0, $len-10);
    $trunk = substr($str, 0, $tail);
    $trunk .= strrev(preg_replace('~^..+?[\s,:]\b|^...~', '...', strrev(substr($str, $tail, $len-$tail))));

    return $trunk;
  }

  private function setMessage($text)
  {

    $this->struct['message']['text'] = self::truncate($text, 300);
  }

  public function getMessage()
  {
    return $this->struct;
  }
}
