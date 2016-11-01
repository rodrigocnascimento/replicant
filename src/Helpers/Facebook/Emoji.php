<?php
namespace Helpers\Facebook;

class Emoji
{

  public $emoji = [
    'saylor' => '⚓️',
    'fire' => '🔥',
    'pirate_skull' => '☠',
    'metal_hands' => '🤘',
    'rat' => '🐀',
    'robot' => '🤖',
    'chillin' => '😎',
    'unicorn' => '🦄',
    'snake' => '🐍',
    'dancer' => '👯',
    'wolf' => '🐺',
    'question' => '🤔'
  ];

  public function __construct()
  {
  }

  public function getRandomEmoji()
  {

    $emojiKeys = array_keys($this->emoji);
    $rand = rand(0, count($emojiKeys) - 1);

    return $this->emoji[$rand];
  }

  public function getEmoji($emoji)
  {

    if (empty($emoji))
    throw new Exception('Precisa de um emoji');

    if (is_array($emoji)) {
      $_e = "";
      foreach ($emoji as $key => $e) {
        $_e .= self::getEmoji($e) . ' ';
      }
      return $_e;
    } else {
      return $this->emoji[$emoji];
    }
  }
}
