<?php

namespace BasiliskBundle\Hook\Listener;

interface HookListenerInterface
{
    public function handle(array $hookEvent);
    public function supports(array $hookEvent): bool;
}
