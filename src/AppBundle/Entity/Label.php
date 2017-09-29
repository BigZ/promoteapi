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
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Halapi\Annotation\Embeddable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Label.
 *
 * @ORM\Table(name="label")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LabelRepository")
 * @ORM\HasLifecycleCallbacks
 *
 * @ExclusionPolicy("all")
 */
class Label
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Expose
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     *
     * @Expose
     */
    private $name;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     *
     * @Expose
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     *
     * @Expose
     */
    private $description;

    /**
     * @var array
     *
     * @ORM\ManyToMany(targetEntity="Artist", mappedBy="labels")
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
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $createdAt;

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
     * @return Label
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
     * Set slug.
     *
     * @param string $slug
     *
     * @return Label
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Label
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return array
     */
    public function getArtists()
    {
        return $this->artists;
    }

    /**
     * @param array $artists
     *
     * @return $this
     */
    public function setArtists(array $artists)
    {
        $this->artists = $artists;

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
