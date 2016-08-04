<?php

namespace AppBundle\Handler;

use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\VisitorInterface;

class FilenameHandler implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'string',
                'method' => 'serializeFilenameToJson',
            ],
        ];
    }

    public function serializeFilenameToJson(VisitorInterface $visitor, $filename, array $type)
    {
        return 'http://zob.com/'.$filename;
    }
}
