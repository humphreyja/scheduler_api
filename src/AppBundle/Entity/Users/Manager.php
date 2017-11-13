<?php
namespace AppBundle\Entity\Users;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;
use AppBundle\Entity\Shift;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation as JSON;

/**
 * STI USER/Manager.
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Users\ManagerRepository")
 *
 * @JSON\ExclusionPolicy("all")
 */
class Manager extends User implements UserInterface
{
    public static function createUser($username, $plainPassword, $name, $email, $phone)
    {
        $user = new Manager();
        $user->setUsername($username);
        $user->setPlainPassword($plainPassword);
        $user->setName($name);
        $user->setEmail($email);
        $user->setPhone($phone);
        return $user;
    }
    /**
    * @ORM\OneToMany(targetEntity="AppBundle\Entity\Shift", mappedBy="manager")
    *
    * @JSON\MaxDepth(1)
    * @JSON\Groups({"shifts_group"})
    * @JSON\Expose
    */
    protected $shifts;

    /**
     * Returns a list of Roles that this user has for the UserInterface interface.
     * @return Array
     */
    public function getRoles()
    {
        return ['ROLE_MANAGER', 'ROLE_API'];
    }
}
