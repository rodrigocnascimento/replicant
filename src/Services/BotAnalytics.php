<?php
namespace Services;

use \Helpers\cURL;

class BotAnalytics
{
    //https://tracker.dashbot.io/track?platform=facebook&v=0.7.4-rest&type=incoming&apiKey=F3UvA7puGJ1RMvogsk09HEKKzy9uJq9E5NKoNoji
    public $apiUrl = 'https://tracker.dashbot.io';
    public $apiVersion = '0.7.4-rest';
    public $apiPlatform = 'facebook';
    /**
    * Objeto Helper\cURL
    * @var Helper\cURL
    */
    public $curl = null;
    /**
    * [__construct description]
    * @param array $userInput Entrada http raw
    */
    public function __construct()
    {
        $this->curl = new cURL();
    }
    public function trackBot($recipientId, $payload, $midId)
    {
        $url = $this->buildApiUrl('track', 'outgoing');

        $dataInput = [
            'qs' => [
                'access_token' => getenv('BOT_TOKEN') . 'ZFDE',
            ],
            'uri' => "https://graph.facebook.com/v2.6/me/messages",
            'json' => [
                'message' => $payload,
                'recipient' => [
                    'id' => $recipientId
                ]
            ],
            'method' => 'POST',
            'responseBody' => $midId
        ];

        $reponse = $this->curl->call($url, $dataInput, cURL::TYPE_POST);

        if (!$reponse['success']) {
            error_log(json_encode($reponse));
        }
    }

    public function trackUser($userInput)
    {
        $url = $this->buildApiUrl('track');

        $reponse = $this->curl->call($url, $userInput, cURL::TYPE_POST);

        if (!$reponse['success']) {
            error_log(json_encode($reponse));
        }
    }

    private function buildApiUrl($uri, $type = 'incoming')
    {
        return sprintf(
        '%s/%s?platform=%s&v=%s&type=%s&apiKey=%s',
        $this->apiUrl,
        $uri,
        $this->apiPlatform,
        $this->apiVersion,
        $type,
        getenv('BOT_ANALYTICS_TOKEN'));
    }
}
