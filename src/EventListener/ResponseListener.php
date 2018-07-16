<?php

namespace App\EventListener;

use App\Transformer\EntityTransformer;
use League\Fractal\Resource\Collection;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\Request;

use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\JsonApiSerializer;

/**
 * Class ResponseListener
 * @package App\EventListener
 */
class ResponseListener
{
    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (200 !== $event->getResponse()->getStatusCode()
            || 'application/vnd.api+json' !== $event->getRequest()->headers->get('Content-Type')
        ) {
            return;
        }

        $content = json_decode($event->getResponse()->getContent(), 1);

        $fractal = new Manager();
        $includes = [];
        $inclusion = $event->getRequest()->get('include');

        if ($inclusion) {
            $fractal->parseIncludes($inclusion);
            $includes = explode(',', $inclusion);
        }

        $fractal->setSerializer(new JsonApiSerializer(''));
        $resource = $this->getResource($content, $includes, $this->guessType($event->getRequest()));
        $data = $fractal->createData($resource)->toArray();

        if ($resource instanceof Collection) {
            $data['links'] = $content['_links'];
        }

        $event->getResponse()->setContent(json_encode($data));
    }

    /**
     * @param $content
     * @param $includes
     * @param $type
     * @return Collection|Item
     */
    private function getResource($content, $includes, $type)
    {
        if (isset($content['id'])) {
            return new Item($content, new EntityTransformer($includes), $type);
        }

        return new Collection($content['_embedded'], new EntityTransformer($includes), $type);
    }

    /**
     * Guess an entityType from the route path.
     * Doesn't seem very reliable... (subresources & stuff like that)
     *
     * @param Request $request
     * @return bool|string
     */
    private function guessType(Request $request)
    {
        $path = $request->getPathInfo();

        $enPosition = strpos($path,'/', 1);

        return substr($path, 1, $enPosition ? $enPosition - 2 : strlen($path) - 2);
    }
}