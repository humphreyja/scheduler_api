<?php

namespace AppBundle\Repository;

/**
 * ShiftRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ShiftRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Finds all shifts with an optional date range
     *
     * @param \DateTime|null $startRange
     * @param \DateTime|null $startRange
     *
     * @return Shift[]
     */
    public function findAllByRange($startRange, $endRange) {
        $qb = $this->getEntityManager()
                   ->createQueryBuilder()
                   ->select('shifts')
                   ->from('AppBundle\Entity\Shift', 'shifts')
                   ->orderBy('shifts.startTime', 'ASC');

       if ($startRange) {
           $qb->andWhere('shifts.startTime >= :startRange')
              ->setParameter('startRange', $startRange);
       }

       if ($endRange) {
           $qb->andWhere('shifts.endTime <= :endRange')
              ->setParameter('endRange', $endRange);
       }


       $query = $qb->getQuery();

      return $query->getResult();
    }
}
