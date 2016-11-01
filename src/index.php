<?php
include_once '../vendor/autoload.php';

$settings = include_once 'settings.php';
$botConfigs = $settings['botConfigs'];

use \Helpers\cURL;
use \Controllers\BotController;

/**
 * Exemplo de Requisição enviada para subscribe da page
 *
 * GET /index.php?hub.mode=subscribe&hub.challenge=2127260776&hub.verify_token=my_special_token HTTP/1.1
 * User-Agent: facebookplatform/1.0 (+http://developers.facebook.com)
 * Host: *.host.*
 * Accept: *\/*
 * Accept-Encoding: deflate, gzip
 * X-Forwarded-Proto: https
 * X-Forwarded-For: XX.XX.XXX.XXX
 *
 */

$facebookRequestMode    = $_GET['hub_mode']         ?? null;
$facebookToken          = $_GET['hub_verify_token'] ?? null;
$facebookChallenge      = $_GET['hub_challenge']    ?? null;

$isSubscribe = (!empty($_GET) && $facebookRequestMode === 'subscribe');
$isTokenValid = ($facebookToken === $botConfigs['Facebook']['hub_verify_token']);

$validInscription = $isSubscribe && $isTokenValid;

if ($validInscription) {
    echo $facebookChallenge;
    return;
}

/**
 * Exemplo de requisição comum, vinda do Facebook
 *
 * POST /index.php HTTP/1.1
 * Host: *.host.*
 * Accept: *\/*
 * Accept-Encoding: deflate, gzip
 * Content-Type: application/json
 * X-Hub-Signature: sha1=b2774508eab369be26ff8bf3a61e2a2ed3c7192c
 * Content-Length: 259
 * X-Forwarded-Proto: https
 * X-Forwarded-For: XXX.XXX.XX.XX
 *
 *  {
 *   "object":"page",
 *   "entry":[
 *       {
 *          "id":"PAGE_SCOPE_ID",
 *          "time":1478004964471,
 *          "messaging":[
 *             {
 *                "sender":{
 *                   "id":"USER_CHAT_SCOPE_ID"
 *                },
 *                "recipient":{
 *                   "id":"PAGE_SCOPE_ID"
 *                },
 *                "timestamp":1478004964352,
 *                "message":{
 *                   "mid":"mid.1478004964352:4be33d7e10",
 *                   "seq":639,
 *                   "text":"oi"
 *                }
 *             }
 *          ]
 *       }
 *    ]
 * }
 */

$controller = new BotController($settings['botConfigs']);
$controller->dispatch();
