<?php
namespace AppBundle\Entity\Users;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;
use AppBundle\Entity\Shift;

/**
 * Manager
 * @ORM\Entity
 */
class Manager extends User
{

  /**
   * @ORM\OneToMany(targetEntity="AppBundle\Entity\Shift", mappedBy="manager")
   */
  private $shifts;
}
