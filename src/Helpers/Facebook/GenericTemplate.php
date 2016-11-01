<?php
namespace Helpers\Facebook;

class GenericTemplate
{

  protected $struct = ['message' => ['attachment' => []]];
  protected $shareButton = false;

  public function __construct(array $elements)
  {

    $this->setTemplate($elements);
  }

  private function setTemplate($elements)
  {

    $this->struct['message']['attachment']['type'] = 'template';
    $this->struct['message']['attachment']['payload']['template_type'] = 'generic';
    $this->struct['message']['attachment']['payload']['elements'] = $elements;
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
//       "payload":{
//         "template_type":"generic",
//         "elements":[
//           {
//             "title":"Welcome to Peter\'s Hats",
//             "item_url":"https://petersfancybrownhats.com",
//             "image_url":"https://petersfancybrownhats.com/company_image.png",
//             "subtitle":"We\'ve got the right hat for everyone.",
//             "buttons":[
//                {
//                  "type":"element_share"
//                },
//               {
//                 "type":"web_url",
//                 "url":"https://petersfancybrownhats.com",
//                 "title":"View Website"
//               },
//               {
//                 "type":"postback",
//                 "title":"Start Chatting",
//                 "payload":"DEVELOPER_DEFINED_PAYLOAD"
//               }
//             ]
//           }
//         ]
//       }
//     }
//   }
// }' "https://graph.facebook.com/v2.6/me/messages?access_token=PAGE_ACCESS_TOKEN"
