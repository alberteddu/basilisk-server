<?php

namespace BasiliskBundle\Controller;

use BasiliskBundle\Entity\Profile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WebhookController extends Controller
{
    /**
     * @Route("pusher/webhook")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function webhookAction(Request $request)
    {
        $hookManager = $this->get('basilisk.hook.manager');
        $body = $request->getContent();
        $data = json_decode($body, true);

        foreach ($data['events'] as $event) {
            $hookManager->handleEvent($event);
        }

        return new Response();
    }
    /**
     * @Route("test")
     *
     * @return Response
     */
    public function testAction()
    {
        $this->get('pusher')->trigger('private-basilisk', 'something', ['hey' => 'ho']);

        return new Response();
    }
}
