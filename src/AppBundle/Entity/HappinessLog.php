<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Happiness
 *
 * @ORM\Table(name="happiness")
 * @ORM\Entity(repositoryClass="HappinessLogRepository")
 */
class HappinessLog
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Assert\Type(
     *     type="integer"
     * )
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="external_id", type="integer", nullable=true)
     *
     * @Assert\Type(
     *     type="integer"
     * )
     */
    private $externalId;

    /**
     * @var bool
     *
     * @ORM\Column(name="happy", type="boolean")
     *
     * @Assert\NotBlank
     * @Assert\Type("bool")
     */
    private $happy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @Assert\DateTime
     */
    private $createdAt;


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
     * Set externalId
     *
     * @param integer $externalId
     *
     * @return HappinessLog
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * Get externalId
     *
     * @return int
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * Set happy
     *
     * @param boolean $happy
     *
     * @return HappinessLog
     */
    public function setHappy($happy)
    {
        $this->happy = $happy;

        return $this;
    }

    /**
     * Get happy
     *
     * @return bool
     */
    public function getHappy()
    {
        return $this->happy;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return HappinessLog
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}

