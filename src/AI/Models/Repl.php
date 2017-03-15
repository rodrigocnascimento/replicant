<?php
namespace AI\Models;
/**
 * Regex Processing Language
 * Classe que interpreta a entrada do usuário, baseando-se em regex
 */
class Repl
{
  private $userIntent = [];
  
  protected $pattern;

  public function __construct()
  { }
  /**
   * Retorna a intenção do sender
   * @return [array] Resultado 
   */
  public function getUserIntent()
  {
    return $this->userIntent;
  }
  /**
   * [setUserIntent description]
   * @param [type] $userInput [description]
   */
  public function setUserIntent($userInput)
  {
    array_filter($this->pattern, function($regex, $name) use (&$userInput) {
      $pattern = '/' . implode('|', $regex) . '/i';

      if (preg_match($pattern, $userInput, $matches)) {
        $this->userIntent[] = $name;
      }
    }, ARRAY_FILTER_USE_BOTH);
  }
}
