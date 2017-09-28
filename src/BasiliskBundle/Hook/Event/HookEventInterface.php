<?php

namespace BasiliskBundle\Hook\Event;

interface HookEventInterface
{
    public function getName(): string;
    public function getChannel(): string;
}
