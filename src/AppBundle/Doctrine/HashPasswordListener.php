<?php
namespace AppBundle\Doctrine;
use AppBundle\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Responsible for hashing the users password on create/reset.
 * NOTE: Most of this came from an online source like the Symfony docs.
 */
class HashPasswordListener implements EventSubscriber
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoder $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // Check if entity is a user or is a child of
        if (!$entity instanceof User) {
            return;
        }
        $this->encodePassword($entity);
    }
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // Check if entity is a user or is a child of
        if (!$entity instanceof User) {
            return;
        }
        $this->encodePassword($entity);
        // necessary to force the update to see the change
        $em = $args->getEntityManager();
        $meta = $em->getClassMetadata(get_class($entity));
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }
    public function getSubscribedEvents()
    {
        return ['prePersist', 'preUpdate'];
    }
    /**
     * @param UserInterface $entity
     */
    private function encodePassword(UserInterface $entity)
    {
        if (!$entity->getPlainPassword()) {
            return;
        }
        $encoded = $this->passwordEncoder->encodePassword(
            $entity,
            $entity->getPlainPassword()
        );
        $entity->setPassword($encoded);
    }
}
