<?php

/*
 * This file is part of the promote-api package.
 *
 * (c) Bigz
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace AppBundle\Entity;

/**
 * Class Filename
 * @author Romain Richard
 */
class Filename
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $extension;

    /**
     * Filename constructor.
     * @param $filename
     */
    public function __construct($filename)
    {
        $parts = explode('.', $filename);

        $this->setName($parts[0]);

        $this->setExtension($parts[1]);
    }

    /**
     * @param mixed $extension
     *
     * @return Media
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param mixed $name
     *
     * @return Filename
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string filename
     */
    public function __toString()
    {
        return implode('.', array($this->name, $this->extension));
    }
}
