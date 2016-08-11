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
                'event' => Events::POST_SERIALIZE,
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
        if ($event->getObject() instanceof PaginatedRepresentation) {
            return;
        }
        
        foreach ($this->relationFactory->getRelations($event->getObject()) as $relationKey => $relation) {
            $event->getVisitor()->addData($relationKey, $relation);
        }
    }
}
