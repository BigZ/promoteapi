<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use AppBundle\Annotation\Embeddable;

/**
 * Artist
 *
 * @ORM\Table(name="artist")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArtistRepository")
 *
 * @ExclusionPolicy("all")
 */
class Artist
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
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     * @Expose
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="bio", type="text", nullable=true)
     * @Expose
     */
    private $bio;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="Label", inversedBy="artists")
     * @Embeddable
     */
    protected $labels;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="Gig", inversedBy="artists")
     * @Embeddable
     */
    protected $gigs;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     * @Embeddable
     */
    protected $createdBy;

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
     * @return Artist
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
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Set bio
     *
     * @param string $bio
     *
     * @return Artist
     */
    public function setBio($bio)
    {
        $this->bio = $bio;

        return $this;
    }

    /**
     * Get bio
     *
     * @return string
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * @return array
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * @param $labels
     * @return $this
     */
    public function setLabels($labels)
    {
        $this->labels = $labels;

        return $this;
    }

    /**
     * @return array
     */
    public function getGigs()
    {
        return $this->gigs;
    }

    /**
     * @param $gigs
     * @return $this
     */
    public function setGigs($gigs)
    {
        $this->gigs = $gigs;

        return $this;
    }

    /**
     * @param Gig $gig
     * @return $this
     */
    public function addGig(Gig $gig)
    {
        $this->gigs[] = $gig;

        return $this;
    }

    /**
     * @param Label $label
     * @return $this
     */
    public function addLabel(Label $label)
    {
        $this->labels[] = $label;

        return $this;
    }

    /**
     * Set createdBy.
     *
     * @param User $createdBy
     *
     * @return $this
     */
    public function setCreatedBy(User $createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy.
     *
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
}

