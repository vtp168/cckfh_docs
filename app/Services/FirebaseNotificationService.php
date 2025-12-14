<?php
namespace App\Services;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Messaging\WebPushConfig;
use Kreait\Firebase\Messaging\MulticastSendReport;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Messaging;
use Kreait\Firebase\Factory;


class FirebaseNotificationService
{
    protected Messaging $messaging;

    public function __construct()
    {
        $credentials = config('services.firebase.credentials');

        if (!$credentials || !file_exists($credentials)) {
            throw new \Exception('Firebase credentials file not found');
        }

        $factory = (new Factory)->withServiceAccount($credentials);
        $this->messaging = $factory->createMessaging();

    }
    /**
     * Send a notification to a single device token
     */
    public function sendToToken(string $token, string $title, string $body, array $data = [], array $options = [])
    {
        $notification = Notification::create($title, $body);

        $message = CloudMessage::withTarget('token', $token)
            ->withNotification($notification)
            ->withData($data);

        // Optional: Android / iOS config (example)
        if (!empty($options['android'])) {
            $message = $message->withAndroidConfig(AndroidConfig::fromArray($options['android']));
        }
        if (!empty($options['apns'])) {
            $message = $message->withApnsConfig(ApnsConfig::fromArray($options['apns']));
        }

        try {
            $this->messaging->send($message);
            return ['success' => true];
        } catch (MessagingException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send to multiple tokens (multicast).
     * Note: For >500 tokens, batch in groups (500 is safe per request).
     */
    public function sendToTokens(array $tokens, string $title, string $body, array $data = [])
    {
        $notification = Notification::create($title, $body);
        $message = CloudMessage::new()->withNotification($notification)->withData($data);

        // Break tokens into chunks (max 500 recommended)
        $chunks = array_chunk($tokens, 500);
        $reports = [];

        foreach ($chunks as $chunk) {
            try {
                /** @var MulticastSendReport $report */
                $report = $this->messaging->sendMulticast($message, $chunk);
                $reports[] = [
                    'successful' => $report->successes()->count(),
                    'failed' => $report->failures()->count(),
                    'failures' => array_map(fn($f)=>$f->error()->getMessage(), iterator_to_array($report->failures()))
                ];
            } catch (\Throwable $e) {
                $reports[] = ['error' => $e->getMessage()];
            }
        }

        return $reports;
    }

    /**
     * Send to a topic (broadcast)
     */
    public function sendToTopic(string $topic, string $title, string $body, array $data = [])
    {
        $notification = Notification::create($title, $body);
        $message = CloudMessage::withTarget('topic', $topic)
            ->withNotification($notification)
            ->withData($data);

        try {
            $this->messaging->send($message);
            return ['success' => true];
        } catch (MessagingException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
