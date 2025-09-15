<?php
namespace FacebookCapiPlugin\Service;

use FacebookAds\Api;
use FacebookAds\Object\ServerSide\Event;
use FacebookAds\Object\ServerSide\UserData;
use FacebookAds\Object\ServerSide\CustomData;
use FacebookAds\Object\ServerSide\EventRequest;

class FacebookClient
{
    public function __construct(
        private string $accessToken,
        private string $pixelId,
        private ?string $testEventCode = null
    ) {}

    public static function isAvailable(): bool
    {
        return class_exists(Api::class);
    }

    public function send(array $payload)
    {
        Api::init(null, null, $this->accessToken);

        $userDataIn = isset($payload['user_data']) && is_array($payload['user_data']) ? $payload['user_data'] : [];
        $customDataIn = isset($payload['custom_data']) && is_array($payload['custom_data']) ? $payload['custom_data'] : [];

        $user = new UserData();
        if (!empty($userDataIn['em'])) {
            $rawEmail = (string) $userDataIn['em'];
            $normalized = strtolower(trim($rawEmail));
            // Hash email unless it already looks like a SHA-256 hash
            if (!preg_match('/^[a-f0-9]{64}$/i', $normalized)) {
                $normalized = hash('sha256', $normalized);
            }
            // Prefer setEmail over setEmails
            if (!empty($normalized)) {
                $user->setEmail($normalized);
            }
        }
        if (!empty($_SERVER['REMOTE_ADDR'])) { $user->setClientIpAddress($_SERVER['REMOTE_ADDR']); }
        if (!empty($_SERVER['HTTP_USER_AGENT'])) { $user->setClientUserAgent($_SERVER['HTTP_USER_AGENT']); }
        // Prefer explicit values from payload; otherwise fall back to cookies set by Meta Pixel
        if (!empty($userDataIn['fbp'])) {
            $user->setFbp($userDataIn['fbp']);
        } elseif (!empty($_COOKIE['_fbp'])) {
            $user->setFbp((string) $_COOKIE['_fbp']);
        }
        if (!empty($userDataIn['fbc'])) {
            $user->setFbc($userDataIn['fbc']);
        } elseif (!empty($_COOKIE['_fbc'])) {
            $user->setFbc((string) $_COOKIE['_fbc']);
        }

        $custom = new CustomData();
        if (isset($customDataIn['value'])) { $custom->setValue((float)$customDataIn['value']); }
        if (isset($customDataIn['currency'])) { $custom->setCurrency(strtoupper((string)$customDataIn['currency'])); }
        if (isset($customDataIn['content_name'])) { $custom->setContentName((string)$customDataIn['content_name']); }
        if (isset($customDataIn['num_items'])) { $custom->setNumItems((int)$customDataIn['num_items']); }
        if (isset($customDataIn['content_type'])) { $custom->setContentType((string)$customDataIn['content_type']); }

        // Normalize event_time: default to now; clamp future timestamps (allow small clock skew)
        $now = time();
        $eventTime = isset($payload['event_time']) ? (int)$payload['event_time'] : $now;
        if ($eventTime > $now + 300) { // >5 minutes in the future
            $eventTime = $now;
        }
        // Facebook allows up to 7 days in the past; if older, use now
        if ($eventTime < $now - 7 * 24 * 60 * 60) {
            $eventTime = $now;
        }

        $event = (new Event())
            ->setEventName((string)($payload['event_name'] ?? ''))
            ->setEventTime($eventTime)
            ->setActionSource((string)($payload['action_source'] ?? 'website'))
            ->setUserData($user)
            ->setCustomData($custom);

        if (!empty($payload['event_id'])) { $event->setEventId((string)$payload['event_id']); }
        if (!empty($payload['event_source_url'])) { $event->setEventSourceUrl((string)$payload['event_source_url']); }

        $req = (new EventRequest($this->pixelId))->setEvents([$event]);
        if ($this->testEventCode) { $req->setTestEventCode($this->testEventCode); }

        return $req->execute();
    }
}
