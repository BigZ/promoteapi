<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Annotation\Embeddable;
use JMS\Serializer\Annotation\Expose;

/**
 * Gig
 *
 * @ORM\Table(name="gig")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GigRepository")
 */
class Gig
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Expose
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="datetime")
     * @Expose
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="datetime", nullable=true)
     * @Expose
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="venue", type="string", length=255, nullable=true)
     * @Expose
     */
    private $venue;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     * @Expose
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="facebookLink", type="string", length=255, nullable=true, unique=true)
     * @Expose
     */
    private $facebookLink;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="Artist", mappedBy="gigs")
     * @Embeddable
     */
    private $artists;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     * @Embeddable
     */
    protected $createdBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    protected $createdAt;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
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
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set startDate
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
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
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
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set venue
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
     * Get venue
     *
     * @return string
     */
    public function getVenue()
    {
        return $this->venue;
    }

    /**
     * Set address
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
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set facebookLink
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
     * Get facebookLink
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
     * @return $this
     */
    public function setArtists($artists)
    {
        $this->artists = $artists;

        return $this;
    }

    /**
     * @param Artist $artist
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
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}

