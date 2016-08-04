<?php

namespace bigz\halapi;

use bigz\halapi\Factory\RelationFactory;
use bigz\halapi\Subscriber\JsonEventSubscriber;
use JMS\Serializer\EventDispatcher\EventDispatcherInterface;
use JMS\Serializer\SerializerBuilder;

class HALAPIBuilder
{
    /**
     * @var SerializerBuilder
     */
    private $serializerBuilder;

    private $router;

    private $annotationReader;

    public function __construct(
        RelationFactory $relationFactory,
        SerializerBuilder $serializerBuilder = null
    ) {
        $this->relationFactory = $relationFactory;
        $this->serializerBuilder = $serializerBuilder ?: SerializerBuilder::create();
    }

    public function getSerializer()
    {
        $eventSubscribers = array(
            new JsonEventSubscriber($this->relationFactory),
        );

        $this->serializerBuilder
            ->addDefaultListeners()
            ->configureListeners(function (EventDispatcherInterface $dispatcher) use ($eventSubscribers) {
                foreach ($eventSubscribers as $eventSubscriber) {
                    $dispatcher->addSubscriber($eventSubscriber);
                }
            })
        ;

        return $this->serializerBuilder->build();
    }
}
