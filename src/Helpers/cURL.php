<?php
namespace Helpers;

class cURL
{
  /**
  * Request type GET
  */
  const TYPE_GET = "get";
  /**
  * Request type POST
  */
  const TYPE_POST = "post";
  /**
  * Request type DELETE
  */
  const TYPE_DELETE = "delete";
  /**
  * Request to API
  *
  * @param string $url
  * @param array  $data
  * @param string $type Type of request (GET|POST|DELETE)
  * @return array
  */
  public function call($url, $data, $type = self::TYPE_POST)
  {

    $headers = [
      'Content-Type: application/json',
    ];

    if ($type == self::TYPE_GET) {
      $url .= '?'.http_build_query($data);
    }

    $process = curl_init($url);
    curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($process, CURLOPT_HEADER, false);
    curl_setopt($process, CURLOPT_TIMEOUT, 30);

    if($type == self::TYPE_POST || $type == self::TYPE_DELETE) {
      curl_setopt($process, CURLOPT_POST, 1);
      curl_setopt($process, CURLOPT_POSTFIELDS, http_build_query($data));
    }
    if ($type == self::TYPE_DELETE) {
      curl_setopt($process, CURLOPT_CUSTOMREQUEST, "DELETE");
    }
    curl_setopt($process, CURLOPT_RETURNTRANSFER, true);

    $return = curl_exec($process);
// var_dump($return);
    if($errno = curl_errno($process)) {
        $error_message = curl_strerror($errno);
        error_log("cURL error ({$errno}):\n {$error_message}");
    }

    curl_close($process);

    // botlog([$return], false, "cURL return");

    return json_decode($return, true);
  }
}
