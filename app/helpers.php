<?php
use GuzzleHttp\Client;

function sendVerifySms(string $phoneNumber, string $verifyCode)
  {
    $client = new Client();

    $url = 'https://api.sms.ir/v1/send/verify';
    $headers = [
      'Accept' => 'application/json',
      'X-API-KEY' => 'kbXRwwN1VYa7bf9BHGZGp3n1IfWHAOWZ4hcirmQFedlbmTzZNHIrTt1QasvGvioC',
    ];
    $body = [
      'mobile' => $phoneNumber,
      'templateId' => 254876,
      "parameters" => [
        [
          "name" => "Code",
          "value" => $verifyCode,
        ]
      ]
    ];

    $sendSmsResponse = $client->request('POST', $url, [
      'headers' => $headers,
      'json' => $body,
    ]);

    return $sendSmsResponse;
  }