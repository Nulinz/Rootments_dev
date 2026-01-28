<?php

namespace App\Services;

use Google\Client;
use Google\Service\FirebaseCloudMessaging;
use Google\Service\FirebaseCloudMessaging\Message;
use Google\Service\FirebaseCloudMessaging\Notification;
use Google\Service\FirebaseCloudMessaging\AndroidConfig;
use Google\Service\FirebaseCloudMessaging\SendMessageRequest;

class FirebaseService
{
    protected $client;
    protected $messaging;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('app/firebase.json'));
        $this->client->addScope(FirebaseCloudMessaging::CLOUD_PLATFORM);

        $this->messaging = new FirebaseCloudMessaging($this->client);
    }

   public function sendNotification($token, $title, $body)
{
    $message = new Message([
        'token' => $token,
        'notification' => new Notification([
            'title' => $title,
            'body' => $body,
        ]),
        'android' => new AndroidConfig([
            'priority' => 'high',
        ]),
        'apns' => [
            'payload' => [
                'aps' => [
                    'alert' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'sound' => 'sound.wav',
                    'content-available' => 1,
                ],
            ],
        ],
    ]);

    $sendRequest = new SendMessageRequest([
        'message' => $message
    ]);

    try {
        $response = $this->messaging->projects_messages->send(
            "projects/" . config('firebase.project_id'),
            $sendRequest
        );
        return $response;
    } catch (\Exception $e) {
        return ['error' => $e->getMessage()];
    }
}

}
