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

    /**
     * HALAPIBuilder constructor.
     *
     * @param RelationFactory        $relationFactory
     * @param SerializerBuilder|null $serializerBuilder
     */
    public function __construct(
        RelationFactory $relationFactory,
        SerializerBuilder $serializerBuilder = null
    ) {
        $this->relationFactory = $relationFactory;
        $this->serializerBuilder = $serializerBuilder ?: SerializerBuilder::create();
    }

    /**
     *
     * @return \JMS\Serializer\Serializer
     */
    public function getSerializer()
    {
        $this->serializerBuilder
            ->addDefaultListeners()
            ->configureListeners(function (EventDispatcherInterface $dispatcher) use ($eventSubscribers) {
                    $dispatcher->addSubscriber(new JsonEventSubscriber($this->relationFactory));
            })
        ;

        return $this->serializerBuilder->build();
    }
}
