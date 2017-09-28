<?php

namespace BasiliskBundle\Hook\Event;

class UserAwareHookEvent implements UserAwareHookEventInterface
{
    /**
     * @var string
     */
    private $userId;

    /**
     * @var HookEventInterface
     */
    private $hookEvent;

    public function getName(): string
    {
        return $this->hookEvent->getName();
    }

    public function getChannel(): string
    {
        return $this->hookEvent->getChannel();
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public static function createWithPayload(array $payload): HookEventInterface
    {
        $instance = new self;
        $instance->hookEvent = HookEvent::createWithPayload($payload);
        $instance->userId = $payload['user_id'];

        return $instance;
    }
}
