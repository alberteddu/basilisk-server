<?php

namespace BasiliskBundle\Hook;

use BasiliskBundle\Hook\Event\HookEvent;
use BasiliskBundle\Hook\Event\HookEventInterface;
use BasiliskBundle\Hook\Event\UserAwareHookEvent;
use BasiliskBundle\Hook\Listener\HookListenerInterface;

class HookManager
{
    /**
     * @var HookListenerInterface[]
     */
    private $listeners = [];

    public function handleEvent(array $event)
    {
        foreach ($this->listeners as $listener) {
            if ($listener->supports($event)) {
                $listener->handle($event);
            }
        }
    }

    public function addHookListener(HookListenerInterface $listener)
    {
        $this->listeners[] = $listener;
    }
}
