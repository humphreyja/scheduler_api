<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JSON;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as SchedulerApiAssert;

/**
 * Shift
 *
 * @ORM\Table(name="shift")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ShiftRepository")
 * @ORM\HasLifecycleCallbacks
 *
 * @SchedulerApiAssert\RequiresStartTimeBeLessThanEndTime
 *
 * @JSON\ExclusionPolicy("all")
 */
class Shift
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JSON\Groups({"Default"})
     * @JSON\Expose
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Users\Manager", inversedBy="shifts")
     * @ORM\JoinColumn(name="manager_id", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     *
     * @JSON\MaxDepth(2)
     * @JSON\Groups({"manager_details_group"})
     * @JSON\Expose
     */
    private $manager;

    /**
     * @var int
     *
     * @ORM\Column(name="manager_id", type="integer")
     */
    private $managerId;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Users\Employee", inversedBy="shifts")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     * @JSON\MaxDepth(1)
     * @JSON\Groups({"employee_details_group"})
     * @JSON\Expose
     */
    private $employee;

    /**
     * @var int
     *
     * @ORM\Column(name="employee_id", type="integer")
     */
    private $employeeId;

    /**
     * @JSON\Groups({"same_shift_group"})
     * @JSON\Expose
     */
    private $sameShiftEmployees;

    /**
     * @var float
     * @ORM\Column(name="break", type="float")
     *
     * @Assert\NotBlank()
     *
     * @JSON\Groups({"Default"})
     * @JSON\Expose
     */
    private $break;

    /**
     * @var \DateTime
     * @ORM\Column(name="start_time", type="datetime")
     *
     * @Assert\NotBlank()
     *
     * @JSON\Groups({"Default"})
     * @JSON\Expose
     */
    private $startTime;

    /**
     * @var \DateTime
     * @ORM\Column(name="end_time", type="datetime")
     *
     * @Assert\NotBlank()
     *
     * @JSON\Groups({"Default"})
     * @JSON\Expose
     */
    private $endTime;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime")
     * @JSON\Groups({"Default"})
     * @JSON\Expose
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     * @JSON\Groups({"Default"})
     * @JSON\Expose
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
     * Set manager
     *
     * @param Manager $manager
     *
     * @return Shift
     */
    public function setManager($manager)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * Get manager
     *
     * @return Manager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * Set employee
     *
     * @param Employee $employee
     *
     * @return Shift
     */
    public function setEmployee($employee)
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get employee
     *
     * @return Employee
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * @JSON\Groups({"check_assignment_group"})
     * @JSON\VirtualProperty()
     */
    public function isUnassigned()
    {
        if (empty($this->employee)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Sets the list of employees also working during this shift.
     *
     * @param [] $employees
     *
     * @return Shift
     */
    public function setSameShiftEmployees($employees)
    {
        $this->sameShiftEmployees = $employees;
        return $this;
    }

    /**
     * Not ORM based, this must be separately queried and set.  Otherwise it
     * could cause potential issues of autoloading extra data.
     *
     * @return []
     */
    public function getSameShiftEmployees()
    {
        return $this->sameShiftEmployees;
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

    /**
     * Sets timestamps on create/update
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
     public function updatedTimestamps()
     {
         $this->setUpdatedAt(new \DateTime('now'));

         if ($this->getCreatedAt() == null) {
             $this->setCreatedAt(new \DateTime('now'));
         }
     }
}
