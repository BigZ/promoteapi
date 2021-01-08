<?php

/*
 * This file is part of the promote-api package.
 *
 * (c) Bigz
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use WizardsRest\Annotation\Exposable;
use WizardsRest\Annotation\Embeddable;

/**
 * @ORM\Table(name="artist")
 * @ORM\Entity(repositoryClass="App\Repository\ArtistRepository")
 * @ORM\HasLifecycleCallbacks
 *
 * @UniqueEntity(
 *     fields={"id"},
 *     errorPath="artist",
 *     message="An artist is already registered with the same id."
 * )
 * 
 */
class Artist
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Exposable
     */
    private int $id;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     *
     * @Exposable
     */
    private string $name;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     *
     * @Exposable
     */
    private $slug;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="bio", type="text", nullable=true)
     *
     * @Exposable
     */
    private $bio;

    /**
     * @ORM\ManyToMany(targetEntity="Label", inversedBy="artists")
     *
     * @Embeddable
     */
    protected $labels;

    /**
     * @ORM\ManyToMany(targetEntity="Gig", inversedBy="artists")
     *
     * @Embeddable
     */
    protected ?iterable $gigs;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Exposable
     */
    private ?string $imageName;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $createdAt;

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
     * @return Artist
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
     * Set bio.
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
     * Get bio.
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
     * @param array $labels
     *
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
     * @param array $gigs
     *
     * @return $this
     */
    public function setGigs($gigs)
    {
        $this->gigs = $gigs;

        return $this;
    }

    /**
     * @return $this
     */
    public function addGig(Gig $gig)
    {
        $this->gigs[] = $gig;

        return $this;
    }

    /**
     * @return $this
     */
    public function addLabel(Label $label)
    {
        $this->labels[] = $label;

        return $this;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return $this
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param string $imageName
     *
     * @return $this
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
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
