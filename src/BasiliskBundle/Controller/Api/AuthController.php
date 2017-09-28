<?php

namespace BasiliskBundle\Controller\Api;

use BasiliskBundle\Entity\Profile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Todo: make this a rest controller
 * Todo: use some other components for validating
 * Todo: repeat less shit
 */
class AuthController extends Controller
{
    /**
     * @Route("auth")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function authAction(Request $request)
    {
        $pusher = $this->get('pusher');
        $channelName = $request->get('channel_name');
        $socketId = $request->get('socket_id');

        $explodeChannelName = explode('-', $channelName);
        $channelType = $explodeChannelName[0];
        $channelSpecificName = implode('-', array_slice($explodeChannelName, 1));

        if ('private' === $channelType && 'basilisk' !== $channelSpecificName) {
            if ($this->getUser()->getId()->toString() !== $channelSpecificName) {
                return new Response('', 401);
            }
        }

        /** @var Profile $user */
        $user = $this->getUser();

        if (explode('-', $channelName)[0] === 'presence') {
            $response = $pusher->presence_auth($channelName, $socketId, $user->getId(), [
                'username' => $user->getUsername(),
            ]);
        } else {
            $response = $pusher->socket_auth($channelName, $socketId);
        }

        return new Response($response);
    }

    /**
     * @Route("signup")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function signupAction(Request $request)
    {
        $email = $request->get('email');
        $username = $request->get('username');
        $password = $request->get('password');
        $userManager = $this->get('fos_user.user_manager');

        $userByEmail = $userManager->findUserByEmail($email);
        $userByName = $userManager->findUserByUsername($username);
        $errorMessage = false;

        if ($userByEmail) {
            $errorMessage = 'Email already exists.';
        } elseif ($userByName) {
            $errorMessage = 'Username taken.';
        } elseif (strlen($username) < 4 || strlen($username) > 15) {
            $errorMessage = 'Username must be shorter > 3 and < 15.';
        }

        if ($errorMessage) {
            return new JsonResponse([
                'message' => $errorMessage,
            ], 401);
        }

        /** @var Profile $user */
        $user = $userManager->createUser();
        $user->setEmail($email);
        $user->setUsername($username);
        $user->setPlainPassword($password);
        $user->setEnabled(true);
        $userManager->updateUser($user, true);

        return new JsonResponse([
            'success' => true,
        ]);
    }
}
