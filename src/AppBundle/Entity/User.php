<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Shift;
use AppBundle\Entity\Users\Employee;
use AppBundle\Entity\Users\Manager;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as SchedulerApiAssert;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use JMS\Serializer\Annotation as JSON;

/**
 * Base User class.  Defines most logic related to data design and authentication.  User roles are define
 * by either being an Employee or Manager through Single Table Inheritance.
 *
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="role", type="string")
 * @ORM\DiscriminatorMap({"employee" = "AppBundle\Entity\Users\Employee", "manager" = "AppBundle\Entity\Users\Manager", "" = "User"})
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
 *
 * @SchedulerApiAssert\RequiresEmailOrPhone
 *
 * @JSON\ExclusionPolicy("all")
 */
abstract class User implements UserInterface, \Serializable
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
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Assert\NotBlank()
     *
     * @JSON\Groups({"Default"})
     * @JSON\Expose
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(name="username", type="string", length=25, unique=true)
     *
     * @Assert\NotBlank()
     *
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64)
     */
    protected $password;
    protected $plainPassword;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     *
     * @Assert\NotBlank()
     * @SchedulerApiAssert\RequiresEmailFormat
     *
     * @JSON\Groups({"Default"})
     * @JSON\Expose
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     *
     * @Assert\NotBlank()
     * @SchedulerApiAssert\RequiresPhoneFormat
     *
     * @JSON\Groups({"Default"})
     * @JSON\Expose
     */
    protected $phone;

    /**
     * @var Shift[]
     *
     * @JSON\Groups({"shifts_group"})
     * @JSON\Expose
     */
    protected $shifts;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @JSON\Groups({"Default"})
     * @JSON\Expose
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     * @JSON\Groups({"Default"})
     * @JSON\Expose
     */
    protected $updatedAt;

    // Constructor
    public function __construct()
    {
        $this->isActive = true;
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
     * Set name
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }



    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Get hashed password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get plain password, before hashing.
     *
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set plain password, prepares for hashing
     *
     * @param string $plainPassword
     *
     * @return User
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }


    /**
     * Requred for UserInterface
     */
    public function eraseCredentials() {}

    /**
     * Using bcrypt so salt is not needed.
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Requred for Serializable
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->salt
        ));
    }

    /**
     * Requred for Serializable
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->salt
        ) = unserialize($serialized);
    }


    /**
     * Set role
     *
     * @param string $role
     *
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return User
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
     * @return User
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
