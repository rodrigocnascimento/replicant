<?php
namespace AI;

class WordPattern
{
  private $userIntent = [];
  private $patterns = [
    'greet' => [
      '(\bin(í|i)c(iar|io)\b\s)', 
      '(\breboot\b)',
      '(\bo{1,}((i|e|r){1,})[oier]*\b)',
      '(\b((\s?come(c|ss|s|ç)(o|a|e|ar|ou)))\b)(\s)?'
    ],
    'goodbye' => [
      '(\b((x|tch|ci|ti|at)+(iu|e|é|au|ao)+)\s?\b)',
      '(\b(mais|logo+)\b)',
      '(\b(adeus)\b)',
      '(\b((bye)|(g(o*)dbye))\b)'
    ],
    'thankyou' => [
      '(\b((\s?)+(obrigado)+\s?)\b)',
      '(\b(valeu)\b)',
      '(\b((fico)\s+(\w)+\s)\b)'
    ],
    'whatiknow' => [
      '(\b((o)|(qu.)|((voc(.*)\s)|(vc))|(sabe)|(faz).?)\b)',
      // '((\b(\w\s).?(((q)|(ue|uê)).?\s((vc)|voc(ê|e).?\s(faz|sabe).?\W\w))\s+\b))'
      // (\b\w.?(\s)?.+((q)|(\s)|(ue).+((voc(ê|e)|(vc).?(\s)).?(sabe|faz)))\b)
      // ((\w\s).?(((q)|(ue|uê)).?\s((vc)|voc(ê|e).?\s((faz)|(er)|sabe).?\W\w)())\s+)
    ]
  ];

  public function __construct()
  {
  }
  public function getUserIntent()
  {
    return $this->userIntent;
  }
  public function setUserIntent($userInput)
  {
    array_filter($this->patterns, function($regex, $name) use (&$userInput) {
      $pattern = '/' . implode('|', $regex) . '/i';

      if (preg_match($pattern, $userInput, $matches)) {
        $this->userIntent[] = $name;
      }
    }, ARRAY_FILTER_USE_BOTH);
  }
}
