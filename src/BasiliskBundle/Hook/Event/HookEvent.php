<?php

namespace BasiliskBundle\Hook\Event;

class HookEvent implements HookEventInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $channel;

    public function getName(): string
    {
        return $this->name;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public static function createWithPayload(array $payload): HookEventInterface
    {
        $instance = new self;
        $instance->name = $payload['name'];
        $instance->channel = $payload['channel'];

        return $instance;
    }
}
