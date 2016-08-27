<?php

namespace AppBundle\Subscriber;

use Doctrine\Common\Annotations\Reader;
use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;

class S3PublicUrlSubscriber implements EventSubscriberInterface
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var string
     */
    private $region;

    /**
     * @var string
     */
    private $bucket;

    /**
     * S3PublicUrlSubscriber constructor.
     *
     * @param Reader $reader
     * @param string $region
     * @param string $bucket
     */
    public function __construct(Reader $reader, $region, $bucket)
    {
        $this->reader = $reader;
        $this->bucket = $bucket;
        $this->region = $region;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            array(
                'event' => Events::PRE_SERIALIZE,
                'format' => 'json',
                'method' => 'onPreSerialize',
            ),
        );
    }

    /**
     * Prefixes file names with the amazon bucket url when the have the UploadableField annotation.
     *
     * @param PreSerializeEvent $event
     */
    public function onPreSerialize(PreSerializeEvent $event)
    {
        $object = $event->getObject();
        $reflectionClass = new \ReflectionClass($object);

        foreach ($reflectionClass->getProperties() as $property) {
            if ($field = $this->reader->getPropertyAnnotation($property, UploadableField::class)) {
                // For the tests sake
                $object = $event->getObject();
                $fileName = $field->getFileNameProperty();
                $publicUrl = $this->getPublicUrl($object->{'get'.ucfirst($fileName)}());
                $object->{'set'.ucfirst($fileName)}($publicUrl);
            }
        }
    }

    /**
     * @param $filename
     *
     * @return string
     */
    private function getPublicUrl($filename)
    {
        return sprintf(
            'https://s3.%s.amazonaws.com/%s/%s',
            $this->region,
            $this->bucket,
            $filename
        );
    }
}
