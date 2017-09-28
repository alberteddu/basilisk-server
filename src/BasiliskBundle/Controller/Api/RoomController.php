<?php

namespace BasiliskBundle\Controller\Api;

use BasiliskBundle\Entity\Profile;
use BasiliskBundle\Entity\Room;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RoomController extends Controller
{
    /**
     * @Route("room", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createRoomAction(Request $request)
    {
        $name = $request->get('name');
        $description = $request->get('description');
        $em = $this->get('doctrine.orm.default_entity_manager');

        $room = new Room();
        $room->setName($name);
        $room->setDescription($description);

        $em->persist($room);
        $em->flush();

        $this->get('pusher')->trigger('private-basilisk', 'basilisk.event.rooms', [
            'rooms' => array_map(function(Room $room) {
                return $room->toArray();
            }, $em->getRepository(Room::class)->findAll()),
        ]);

        return new JsonResponse(['room' => $room->toArray()]);
    }
}
