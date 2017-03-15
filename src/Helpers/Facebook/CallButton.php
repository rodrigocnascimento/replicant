<?php
namespace Helpers\Facebook;

class CallButton
{

  protected $struct = ['message' => ['attachment' => []]];
  protected $shareButton = false;

  public function __construct(array $payload)
  {

    $this->setTemplate($payload);
  }

  private function setTemplate($payload)
  {

    $this->struct['message']['attachment']['type'] = 'template';
    $this->struct['message']['attachment']['payload'] = $payload;
  }

  public function getTemplate()
  {
    return $this->struct;
  }
}
// curl -X POST -H "Content-Type: application/json" -d '{
//   "recipient":{
//     "id":"USER_ID"
//   },
//   "message":{
//     "attachment":{
//       "type":"template",
//          "payload":{
//             "template_type":"button",
//             "text":"Need further assistance? Talk to a representative",
//             "buttons":[
//                {
//                   "type":"phone_number",
//                   "title":"Call Representative",
//                   "payload":"+15105551234"
//                }
//             ]
//          }
//     }
//   }
// }' "https://graph.facebook.com/me/messages?access_token=PAGE_ACCESS_TOKEN"