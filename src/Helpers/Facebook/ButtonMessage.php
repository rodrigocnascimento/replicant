<?php
namespace Helpers\Facebook;

class ButtonMessage
{

  protected $struct = ['message' => ['attachment' => []]];

  public function __construct($text = null, array $buttons)
  {

    $this->setTemplate($text, $buttons);
  }

  private function setTemplate($text, $buttons)
  {

    $this->struct['message']['attachment']['type'] = 'template';
    $this->struct['message']['attachment']['payload']['template_type'] = 'button';
    $this->struct['message']['attachment']['payload']['text'] = $text;
    $this->struct['message']['attachment']['payload']['buttons'] = $buttons;
  }

  public function getTemplate()
  {
    return $this->struct;
  }
}

//
// curl -X POST -H "Content-Type: application/json" -d '{
//   "recipient":{
//     "id":"USER_ID"
//   },
//   "message":{
//     "attachment":{
//       "type":"template",
//       "payload":{
//         "template_type":"button",
//         "text":"What do you want to do next?",
//         "buttons":[
//           {
//             "type":"web_url",
//             "url":"https://petersapparel.parseapp.com",
//             "title":"Show Website"
//           },
//           {
//             "type":"postback",
//             "title":"Start Chatting",
//             "payload":"USER_DEFINED_PAYLOAD"
//           }
//         ]
//       }
//     }
//   }
// }' "https://graph.facebook.com/v2.6/me/messages?access_token=PAGE_ACCESS_TOKEN"
// Fields
//
