<?php
namespace Helpers\Facebook;

class ImageMessage
{

  protected $struct = ['message' => ['attachment' => []]];

  public function __construct($url)
  {

    $this->setImage($url);
  }

  private function setImage($url)
  {
    $this->struct['message']['attachment']['type'] = 'image';
    $this->struct['message']['attachment']['payload']['url'] = $url;
  }

  public function getImage()
  {
    return $this->struct;
  }
}
//
// {
//   "recipient":{
//     "id":"USER_ID"
//   },
//   "message":{
//     "attachment":{
//       "type":"image",
//       "payload":{
//         "url":"https://petersapparel.com/img/shirt.png"
//       }
//     }
//   }
// }' "https://graph.facebook.com/v2.6/me/messages?access_token=PAGE_ACCESS_TOKEN"
