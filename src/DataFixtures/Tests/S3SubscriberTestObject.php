<?php

/*
 * This file is part of the promote-api package.
 *
 * (c) Bigz
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace App\DataFixtures\Tests;

/**
 * Class TestObject
 * @author Romain Richard
 */
class S3SubscriberTestObject
{
    /**
     * @Vich\UploadableField(mapping="artist_image", fileNameProperty="imageName")
     */
    private $imageFile;

    /**
     * @var string
     */
    private $imageName;

    /**
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * @param $imageName
     *
     * @return string
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this->imageName;
    }
}
