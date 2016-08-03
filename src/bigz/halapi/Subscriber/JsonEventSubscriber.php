<?php

namespace bigz\halapi\Subscriber;

use bigz\halapi\Factory\RelationFactory;
use bigz\halapi\Representation\PaginatedRepresentation;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;

class JsonEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var RelationFactory
     */
    private $relationFactory;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            array(
                'event'  => Events::POST_SERIALIZE,
                'format' => 'json',
                'method' => 'onPostSerialize',
            ),
        );
    }


    public function __construct(RelationFactory $relationFactory)
    {
        $this->relationFactory = $relationFactory;
    }
    
    public function onPostSerialize(ObjectEvent $event)
    {
        $object = $event->getObject();
        if ($object instanceof PaginatedRepresentation) {
            return;
        }

        $event->getVisitor()->addData('_links', $this->relationFactory->getLinks($event->getObject()));
        $embedded = $this->relationFactory->getEmbedded($event->getObject());

        if ($embedded) {
            $event->getVisitor()->addData('_embedded', $embedded);
        }
    }
}