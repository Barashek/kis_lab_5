<?php

namespace App\Controller;

use App\Controller\TokenAuthenticatedController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Controller\ErrorController;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenSubscriber extends AbstractController implements EventSubscriberInterface
{

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();

        $method = $controller[1];
        $controller = $controller[0];

        $entityName = $this->getEntityName($controller);
        $methods = ['create', 'delete', 'update'];
        $entities = ['Company', 'Vacancy'];

        if (in_array($entityName, $entities) && in_array($method, $methods)) {
            $request = Request::createFromGlobals();

            if (!empty($request->headers->get('authorization'))) {
                $auth = $request->headers->get('authorization');

                list($user, $token) = explode(' ', $auth);
                $decodedToken = base64_decode($token);

                if ($this->isTokenValid($decodedToken)) {
                    if (!$this->isEligible($entityName, $method, $decodedToken)) {
                        $event->setController(
                            function () {
                                return new Response('You are not eligible for this action.', 403);
                            });
                    }
                } else {
                    $event->setController(
                        function () {
                            return new Response('Invalid token', 403);
                        });
                }
            } else {
                $event->setController(
                    function () {
                        return new Response('Need a token for authorization', 403);
                    });
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }


    private function getEntityName($object): string
    {
        $path = "App\Controller\\";
        $controllerWord = "Controller";
        $className = get_class($object);
        $className = str_replace($path, '', $className);
        $className = str_replace($controllerWord, '', $className);
        return $className;
    }

    private function isEligible(string $entity, string $method, $token): bool
    {
        $rights = stristr($token, "{$entity}:");
        $rights = explode(';', $rights);
        $right = strpos($rights[0], $method);
        if ($right !== false) {
            return true;
        }
        return false;
    }

    private function isTokenValid($token)
    {
        if (preg_match_all('/^\S*\.(\w*:((create,?)?(update,?)?(delete,?)?);?)*$/', $token) == 1) {
            return true;
        }
        return false;
    }
}