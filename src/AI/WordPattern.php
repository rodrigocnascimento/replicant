<?php
namespace AI;

class WordPattern
{


  private $getStarted = ['reboot', 'iniciar', 'inicio', 'começar', 'comecar', 'de novo', 'novo', 'início', 'começar de novo', 'começar do início', 'começar do inicio', 'começar do começo', 'começar do comeco'];

  private $meetGreet = ['oi', 'oii', 'oiii', 'oiiii', 'oiiiii', 'oiiiiii', 'olá', 'ei', 'ola', 'olar', 'alô', 'alo'];

  private $goodBye = ['xau', 'tchau', 'ciao', 'bye', 'adeus', 'até mais', 'até', 'ate mais', 'ate'];

  private $whatIKnow = ['o que você sabe?', 'o que você sabe', 'o q vc sabe?', 'o q vc sabe', 'o q vc sabe?', 'o que você faz?', 'o que você faz', 'o que vc faz'
  , 'o que vc faz', 'como funciona', 'como funciona?', 'o que vc sabe', 'o que vc sabe?'];

  public function setUserIntent($userInput)
  {

    foreach (['getStarted', 'meetGreet', 'goodBye', 'whatIKnow'] as $key => $value) {
      if (in_array(strtolower($userInput), $this->{$value})) {
        return $value;
      }
    }
  }
}
