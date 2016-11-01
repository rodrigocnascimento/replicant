<?php
namespace Helpers\Facebook;

class ShareMessage
{

  protected $struct = ['message' => ['attachment' => []]];

  public function __construct(array $elements)
  {

    $this->setTemplate($elements);
  }

  private function setTemplate($elements)
  {


    $this->struct['message']['attachment']['type'] = 'template';
    $this->struct['message']['attachment']['payload']['template_type'] = 'button';
    $this->struct['message']['attachment']['payload']['elements'] = $elements;
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
//         "template_type":"generic",
//         "elements":[
//           {
//             "title":"Breaking News: Record Thunderstorms",
//             "subtitle":"The local area is due for record thunderstorms over the weekend.",
//             "image_url":"https://thechangreport.com/img/lightning.png",
//             "buttons":[
//               {
//                 "type":"element_share"
//               }
//             ]
//           }
//         ]
//       }
//     }
//   }
// }' "https://graph.facebook.com/me/messages?access_token=PAGE_ACCESS_TOKEN"
