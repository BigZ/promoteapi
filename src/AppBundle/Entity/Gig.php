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

use Doctrine\ORM\Mapping as ORM;
use Halapi\Annotation\Embeddable;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;

/**
 * Gig.
 *
 * @ORM\Table(name="gig")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GigRepository")
 * @ORM\HasLifecycleCallbacks
 *
 * @ExclusionPolicy("all")
 */
class Gig
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMS\Expose
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     *
     * @JMS\Expose
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @Assert\NotBlank()
     * @Assert\DateTime()
     *
     * @ORM\Column(name="startDate", type="datetime")
     *
     * @JMS\Expose
     * @JMS\Type("DateTime<'Y-m-d\TH:i:s.000\Z'>")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @Assert\DateTime()
     *
     * @ORM\Column(name="endDate", type="datetime", nullable=true)
     *
     * @JMS\Expose
     * @JMS\Type("DateTime<'Y-m-d\TH:i:s.000\Z'>")
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="venue", type="string", length=255, nullable=true)
     *
     * @JMS\Expose
     */
    private $venue;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="address", type="string", length=255)
     *
     * @JMS\Expose
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="facebookLink", type="string", length=255, nullable=true, unique=true)
     *
     * @JMS\Expose
     */
    private $facebookLink;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="Artist", mappedBy="gigs")
     *
     * @Embeddable
     */
    private $artists;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     *
     * @Embeddable
     */
    protected $createdBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Gig
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set startDate.
     *
     * @param \DateTime $startDate
     *
     * @return Gig
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate.
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate.
     *
     * @param \DateTime $endDate
     *
     * @return Gig
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate.
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set venue.
     *
     * @param string $venue
     *
     * @return Gig
     */
    public function setVenue($venue)
    {
        $this->venue = $venue;

        return $this;
    }

    /**
     * Get venue.
     *
     * @return string
     */
    public function getVenue()
    {
        return $this->venue;
    }

    /**
     * Set address.
     *
     * @param string $address
     *
     * @return Gig
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address.
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set facebookLink.
     *
     * @param string $facebookLink
     *
     * @return Gig
     */
    public function setFacebookLink($facebookLink)
    {
        $this->facebookLink = $facebookLink;

        return $this;
    }

    /**
     * Get facebookLink.
     *
     * @return string
     */
    public function getFacebookLink()
    {
        return $this->facebookLink;
    }

    /**
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param $createdBy
     *
     * @return $this
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return array
     */
    public function getArtists()
    {
        return $this->artists;
    }

    /**
     * @param $artists
     *
     * @return $this
     */
    public function setArtists($artists)
    {
        $this->artists = $artists;

        return $this;
    }

    /**
     * @param Artist $artist
     *
     * @return $this
     */
    public function addArtist(Artist $artist)
    {
        $this->artists[] = $artist;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->setCreatedAt(new \DateTime('now'));
        $this->setUpdatedAt(new \DateTime('now'));
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->setUpdatedAt(new \DateTime('now'));
    }
}
