<?php

namespace BasiliskBundle\Hook\Listeners;

use BasiliskBundle\Entity\Room;
use BasiliskBundle\Hook\HookEventValidator;
use BasiliskBundle\Hook\Listener\HookListenerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Pusher\Pusher;

class ClientInitListener implements HookListenerInterface
{
    /**
     * @var Pusher
     */
    private $pusher;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(Pusher $pusher, EntityManagerInterface $entityManager)
    {
        $this->pusher = $pusher;
        $this->entityManager = $entityManager;
    }

    public function handle(array $hookEvent)
    {
        $rooms = $this->entityManager->getRepository(Room::class)->findAll();

        $this->pusher->trigger(
            $hookEvent['channel'],
            'basilisk.event.rooms',
            ['rooms' => array_map(function(Room $room) {
                return $room->toArray();
            }, $rooms)]
        );
    }

    public function supports(array $hookEvent): bool
    {
        return
            HookEventValidator::nameIs($hookEvent, 'client_event') &&
            HookEventValidator::keyExists($hookEvent, 'channel') &&
            HookEventValidator::eventIs($hookEvent, 'client-init');
    }
}
