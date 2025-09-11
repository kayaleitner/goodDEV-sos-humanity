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
        if (!empty($userDataIn['em'])) { $user->setEmails([(string) $userDataIn['em']]); }
        if (!empty($_SERVER['REMOTE_ADDR'])) { $user->setClientIpAddress($_SERVER['REMOTE_ADDR']); }
        if (!empty($_SERVER['HTTP_USER_AGENT'])) { $user->setClientUserAgent($_SERVER['HTTP_USER_AGENT']); }
        if (!empty($userDataIn['fbp'])) { $user->setFbp($userDataIn['fbp']); }
        if (!empty($userDataIn['fbc'])) { $user->setFbc($userDataIn['fbc']); }

        $custom = new CustomData();
        if (isset($customDataIn['value'])) { $custom->setValue((float)$customDataIn['value']); }
        if (isset($customDataIn['currency'])) { $custom->setCurrency((string)$customDataIn['currency']); }
        if (isset($customDataIn['content_name'])) { $custom->setContentName((string)$customDataIn['content_name']); }
        if (isset($customDataIn['num_items'])) { $custom->setNumItems((int)$customDataIn['num_items']); }
        if (isset($customDataIn['content_type'])) { $custom->setContentType((string)$customDataIn['content_type']); }

        $event = (new Event())
            ->setEventName((string)($payload['event_name'] ?? ''))
            ->setEventTime((int)($payload['event_time'] ?? time()))
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
