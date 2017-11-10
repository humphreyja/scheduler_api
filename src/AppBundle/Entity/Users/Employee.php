<?php
namespace AppBundle\Entity\Users;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;
use AppBundle\Entity\Shift;

/**
 * Employee
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Users\EmployeeRepository")
 */
class Employee extends User
{

  /**
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\Shift", mappedBy="employee")
   */
  private $shifts;
}
