<?php

namespace BasiliskBundle\Controller\Api;

use BasiliskBundle\Entity\Message;
use BasiliskBundle\Entity\Room;
use BasiliskBundle\Message\MessageType;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller
{
    /**
     * @Route("messages", methods={"GET"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getMessagesAction(Request $request)
    {
        $messagesRepository = $this->get('doctrine.orm.default_entity_manager')->getRepository(Message::class);
        $roomsRepository = $this->get('doctrine.orm.default_entity_manager')->getRepository(Room::class);
        $roomId = $request->get('room');

        if (!Uuid::isValid($roomId)) {
            return new JsonResponse(null, 400);
        }

        $room = $roomsRepository->findOneById(Uuid::fromString($roomId));

        if (!$room) {
            // todo: return something meaningful
            return new JsonResponse(null, 404);
        }

        return new JsonResponse([
            'messages' => array_map(function (Message $message) {
                return $message->toArray();
            }, $messagesRepository->findByRoom($room))
        ]);
    }

    /**
     * @Route("message/text", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function sendTextMessageAction(Request $request)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');
        $messagesRepository = $em->getRepository(Message::class);
        $roomsRepository = $em->getRepository(Room::class);
        $roomId = $request->get('room');

        if (!Uuid::isValid($roomId)) {
            return new JsonResponse(null, 400);
        }

        $room = $roomsRepository->findOneById(Uuid::fromString($roomId));

        if (!$room) {
            // todo: return something meaningful
            return new JsonResponse(null, 404);
        }

        $message = $request->get('text');
        $userId = $this->getUser()->getId()->toString();
        $payload = ['text' => $message, 'user' => $userId];
        $newMessage = new Message();
        $newMessage->setPayload($payload);
        $newMessage->setRoom($room);
        $newMessage->setType(MessageType::TEXT);
        $em->persist($newMessage);
        $em->flush();

        return new JsonResponse(['message' => $newMessage->toArray()]);
    }
}
