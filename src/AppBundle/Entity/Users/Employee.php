<?php
namespace AppBundle\Entity\Users;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;
use AppBundle\Entity\Shift;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation as JSON;

/**
 * STI USER/Employee.
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Users\EmployeeRepository")
 *
 * @JSON\ExclusionPolicy("all")
 */
class Employee extends User implements UserInterface
{
    public static function createUser($username, $plainPassword, $name, $email, $phone)
    {
        $user = new Employee();
        $user->setUsername($username);
        $user->setPlainPassword($plainPassword);
        $user->setName($name);
        $user->setEmail($email);
        $user->setPhone($phone);
        return $user;
    }

   /**
    * List of shifts related to the employee.
    * NOTE: My understanding of Doctrine's Single Table Inheritance with the JMS Serializer is a bit rough yet so I had to have this also defined
    *       in the parent class in order for the serializer to work.
    *
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Shift", mappedBy="employee")
    *
    * @JSON\MaxDepth(1)
    * @JSON\Groups({"shifts_group"})
    * @JSON\Expose
    */
    protected $shifts;

    /**
     * @return Shift[]
     */
    public function getShifts()
    {
        return $this->shifts;
    }

    /**
     * Returns a list of Roles that this user has for the UserInterface interface.
     * @return Array
     */
    public function getRoles()
    {
        return array('ROLE_EMPLOYEE', 'ROLE_API');
    }
}
