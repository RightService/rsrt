<?php
namespace Drupal\county_redirect\EventSubscriber;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\node\Entity\Node;

class CountyRedirect implements EventSubscriberInterface
{

    public function checkForRedirection(RequestEvent $event)
    {
        $baseUrl = $event->getRequest()->getBaseUrl();
        $uri = $event->getRequest()->getRequestUri();
        $isSingle = substr_count($uri, '/') === 1;
        $attr = $event->getRequest()->attributes;
        if ($isSingle &&
            null !== $attr &&
            null !== $attr->get('node') &&
            $attr->get('node') instanceof Node &&
            $attr->get('node')->get('type')->getString() === 'county'
        ) {
            $event->setResponse(new RedirectResponse($baseUrl.'/servicetypes'.$uri));
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        $events[KernelEvents::REQUEST][] = array('checkForRedirection');

        return $events;
    }
}
