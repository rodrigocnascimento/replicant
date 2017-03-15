<?php
namespace Helpers\Facebook;

class QuickReply
{

  protected $struct = ['message' => []];

  public function __construct($text = null, array $quickReplies)
  {
    $this->setTemplate($text, $quickReplies);
  }

  private function setTemplate($text, $quickReplies)
  {
    $this->struct['message']['text'] = $text;
    $this->struct['message']['quick_replies'] = $quickReplies;
  }

  public function getTemplate()
  {
    return $this->struct;
  }
}

// {
//   "recipient":{
//     "id":"USER_ID"
//   },
//   "message":{
//     "text":"Pick a color:",
//     "quick_replies":[
//       {
//         "content_type":"text",
//         "title":"Red",
//         "payload":"DEVELOPER_DEFINED_PAYLOAD_FOR_PICKING_RED"
//       },
//       {
//         "content_type":"text",
//         "title":"Green",
//         "payload":"DEVELOPER_DEFINED_PAYLOAD_FOR_PICKING_GREEN"
//       }
//     ]
//   }
// }' "https://graph.facebook.com/v2.6/me/messages?access_token=PAGE_ACCESS_TOKEN"
