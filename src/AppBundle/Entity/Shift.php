<?php

namespace AppBundle\Entity;

/**
 * Shift
 */
class Shift
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="manager_id", referencedColumnName="id")
     */
    private $managerId;

    /**
     * @var int
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="employee_id", referencedColumnName="id")
     */
    private $employeeId;

    /**
     * @var float
     */
    private $break;

    /**
     * @var \DateTime
     */
    private $startTime;

    /**
     * @var \DateTime
     */
    private $endTime;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;


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
     * Set managerId
     *
     * @param integer $managerId
     *
     * @return Shift
     */
    public function setManagerId($managerId)
    {
        $this->managerId = $managerId;

        return $this;
    }

    /**
     * Get managerId
     *
     * @return int
     */
    public function getManagerId()
    {
        return $this->managerId;
    }

    /**
     * Set employeeId
     *
     * @param integer $employeeId
     *
     * @return Shift
     */
    public function setEmployeeId($employeeId)
    {
        $this->employeeId = $employeeId;

        return $this;
    }

    /**
     * Get employeeId
     *
     * @return int
     */
    public function getEmployeeId()
    {
        return $this->employeeId;
    }

    /**
     * Set break
     *
     * @param float $break
     *
     * @return Shift
     */
    public function setBreak($break)
    {
        $this->break = $break;

        return $this;
    }

    /**
     * Get break
     *
     * @return float
     */
    public function getBreak()
    {
        return $this->break;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     *
     * @return Shift
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     *
     * @return Shift
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime
     *
     * @return \DateTime
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Shift
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

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Shift
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
