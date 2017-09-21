<?php

/*
 * This file is part of the promote-api package.
 *
 * (c) Bigz
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace test\AppBundle\Subscriber;

use Vich\UploaderBundle\Mapping\Annotation\UploadableField;
use AppBundle\Subscriber\S3PublicUrlSubscriber;
use PHPUnit\Framework\TestCase;
use AppBundle\DataFixtures\Tests\S3SubscriberTestObject as TestObject;

/**
 * Class S3PublicUrlSubscriberTest
 * @author Romain Richard
 */
class S3PublicUrlSubscriberTest extends TestCase
{

    /**
     * Serializing an entity having a field annotated with @UploadableField()
     * should be modified to display the public url of the related fileNameProperty.
     *
     * @covers S3PublicUrlSubscriber
     */
    public function testOnPreSerialize()
    {
        $testObject = new TestObject();
        $testObjectMock = $this->createMock(TestObject::class);
        $readerMock = $this->createMock(\Doctrine\Common\Annotations\Reader::class);
        $eventMock = $this->createMock(\JMS\Serializer\EventDispatcher\PreSerializeEvent::class);
        $fieldMock = $this->createMock(UploadableField::class);

        // We have an image called test.jpg
        $testObjectMock->expects($this->once())->method('getImageName')->willReturn('test.jpg');
        $testObjectMock->expects($this->once())->method('setImageName');

        // A ReflectionClass of a mock will not return the same than on the original class.
        $eventMock->method('getObject')->willReturnOnConsecutiveCalls($testObject, $testObjectMock);
        $fieldMock->expects($this->once())->method('getFileNameProperty')->willReturn('imageName');
        $readerMock->method('getPropertyAnnotation')
            ->willReturnCallback(function ($property, $className) use ($fieldMock) {
                if ('imageFile' === $property->name && UploadableField::class === $className) {
                    return $fieldMock;
                }

                return false;
            });

        $subscriber = new S3PublicUrlSubscriber($readerMock, 'region', 'bucket');
        $subscriber->onPreSerialize($eventMock);
    }

    /**
     * Serializing an entity having a field annotated with @UploadableField()
     * should be modified to display the public url of the related fileNameProperty.
     * Except if it's empty !
     *
     * @covers S3PublicUrlSubscriber
     */
    public function testOnPreSerializeEmpty()
    {
        $testObject = new TestObject();
        $testObjectMock = $this->createMock(TestObject::class);
        $readerMock = $this->createMock(\Doctrine\Common\Annotations\Reader::class);
        $eventMock = $this->createMock(\JMS\Serializer\EventDispatcher\PreSerializeEvent::class);
        $fieldMock = $this->createMock(UploadableField::class);

        // No image uploaded yet
        $testObjectMock->expects($this->once())->method('getImageName')->willReturn(null);
        $testObjectMock->expects($this->never())->method('setImageName');
        // A ReflectionClass of a mock will not return the same than on the original class.
        $eventMock->method('getObject')->willReturnOnConsecutiveCalls($testObject, $testObjectMock);
        $fieldMock->expects($this->once())->method('getFileNameProperty')->willReturn('imageName');
        $readerMock->method('getPropertyAnnotation')
            ->willReturnCallback(function ($property, $className) use ($fieldMock) {
                if ('imageFile' === $property->name && UploadableField::class === $className) {
                    return $fieldMock;
                }

                return false;
            });

        $subscriber = new S3PublicUrlSubscriber($readerMock, 'region', 'bucket');
        $subscriber->onPreSerialize($eventMock);
    }
}
