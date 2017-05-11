<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Conference
 *
 * @ORM\Table(name="conference")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ConferenceRepository")
 */
class Conference
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="venue", type="string", length=255)
     */
    private $venue;

    /**
     * @var string
     *
     * @ORM\Column(name="halls", type="string", length=255)
     */
    private $halls;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var DateTime
     * @ORM\Column(name="created_at", type="datetimetz", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $created;

    /**
     * @var DateTime
     * @ORM\Column(name="appointed_to", type="date", nullable=false)
     */
    private $appointed;

    /**
     * @var Speaker[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Speaker", cascade={"all"})
     * @ORM\JoinTable(name="speakers", joinColumns={@ORM\JoinColumn(name="conference_id", referencedColumnName="id")},inverseJoinColumns={@ORM\JoinColumn(name="speaker_id", referencedColumnName="id")})
     */
    protected $speakers;

    /**
     * @var Lecture[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Lecture", cascade={"all"})
     * @ORM\JoinTable(name="lecturers", joinColumns={@ORM\JoinColumn(name="conference_id", referencedColumnName="id")},inverseJoinColumns={@ORM\JoinColumn(name="lecturer_id", referencedColumnName="id")})
     */
    protected $lectures;

    /**
     * Many Conferences have One Owner.
     * @ORM\ManyToOne(targetEntity="User", inversedBy="conferences")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private $owner;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, options={"default" = 0})
     */
    private $status;

    public function __construct()
    {
        $this->created = new DateTime();
        $this->speakers = new ArrayCollection();
        $this->lectures = new ArrayCollection();
    }


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
     * Set owner
     *
     * @param string $owner
     *
     * @return Conference
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return string
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set venue
     *
     * @param string $venue
     *
     * @return Conference
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
     * Set halls
     *
     * @param string $halls
     *
     * @return Conference
     */
    public function setHalls($halls)
    {
        $this->halls = $halls;

        return $this;
    }

    /**
     * Get halls
     *
     * @return string
     */
    public function getHalls()
    {
        return $this->halls;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Conference
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return Speaker[]|ArrayCollection
     */
    public function getSpeakers()
    {
        return $this->speakers;
    }

    /**
     * @param Speaker[]|ArrayCollection $speakers
     */
    public function setSpeakers($speakers)
    {
        $this->speakers = $speakers;
    }

    /**
     * @return Lecture[]|ArrayCollection
     */
    public function getLectures()
    {
        return $this->lectures;
    }

    /**
     * @param Lecture[]|ArrayCollection $lectures
     */
    public function setLectures($lectures)
    {
        $this->lectures = $lectures;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return DateTime
     */
    public function getAppointed()
    {
        return $this->appointed;
    }

    /**
     * @param DateTime $appointed
     */
    public function setAppointed($appointed)
    {
        $this->appointed = $appointed;
    }
}

