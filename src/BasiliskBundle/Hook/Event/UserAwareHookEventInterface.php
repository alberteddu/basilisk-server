<?php

namespace BasiliskBundle\Hook\Event;

interface UserAwareHookEventInterface extends HookEventInterface
{
    public function getUserId(): string;
}
