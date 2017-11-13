<?php
namespace AppBundle\Entity;

use JMS\Serializer\Annotation as JSON;
/**
 * A class used to organize historical data for employees.
 *
 * @JSON\ExclusionPolicy("all")
 */
class WorkHistory
{

    /**
    * @JSON\Groups({"Default"})
    * @JSON\Expose
    */
    private $hours;

    /**
    * @JSON\Groups({"Default"})
    * @JSON\Expose
    */
    private $weekStartDate;

    /**
    * @JSON\Groups({"Default"})
    * @JSON\Expose
    */
    private $weekEndDate;

    /**
     * Get hours
     *
     * @return int
     */
    public function getHours()
    {
        return $this->hours;
    }

    /**
     * Set hours
     *
     * @param int
     *
     * @return WorkHistory
     */
    public function setHours($hours)
    {
        $this->hours = $hours;
        return $this;
    }

    /**
     * Get week start date
     *
     * @return \DateTime
     */
    public function getWeekStartDate()
    {
        return $this->weekEndDate;
    }

    /**
     * Set week start date
     *
     * @param \DateTime
     *
     * @return WorkHistory
     */
    public function setWeekStartDate($weekStartDate)
    {
        $this->weekStartDate = $weekStartDate;
        return $this;
    }

    /**
     * Get week end date
     *
     * @return \DateTime
     */
    public function getWeekEndDate()
    {
        return $this->weekEndDate;
    }

    /**
     * Set week end date
     *
     * @param \DateTime
     *
     * @return WorkHistory
     */
    public function setWeekEndDate($weekEndDate)
    {
        $this->weekEndDate = $weekEndDate;
        return $this;
    }
}
