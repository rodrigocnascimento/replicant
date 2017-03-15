<?php 
namespace AI\Models\Replicant;

use AI\Models\Repl;

/**
 * Replicant Regex Pattern Functions
 *
 */
class ReplicantRepl extends Repl
{

  protected $pattern = [
    'greet' => [
      '(\bin(í|i)c(iar|io)\b\s)',
      '(\b(oi+)\b)',
      '(\b(oi[ier]+)\b)',
      '(\b(ol.+)+\b)',
      '(\b((\s?come(c|ss|s|ç)(o|a|e|ar|ou)))\b)(\s)?'
    ]
  ];

  public function __construct() { }
}