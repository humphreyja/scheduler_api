<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Users\Employee;
use AppBundle\Entity\Users\Manager;
use AppBundle\Entity\Shift;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Probably a cleaner way to generate a bunch of data...
 * Generates employees, managers, and a bunch of shifts with random assignment.
 */
class ScheduleFixtures extends Fixture
{
    public function load(ObjectManager $dbmanager)
    {
        # Create a bunch of employees
        $employee1 = Employee::createUser("johndoe", "password123", "John Doe", "johndoe@test.com", "123-456-7890");
        $employee2 = Employee::createUser("janedoe", "password123", "Jane Doe", "janedoe@test.com", null);
        $employee3 = Employee::createUser("billybob", "password123", "Billy Bob", "billybob@test.com", "198-765-4321");
        $employee4 = Employee::createUser("kevinbacon", "password123", "Kevin Bacon", "kevinbacon@test.com", "534-765-6524");
        $employee5 = Employee::createUser("jamesbond", "password123", "James Bond", "jamesbond@test.com", "432-007-2353");

        $dbmanager->persist($employee1);
        $dbmanager->persist($employee2);
        $dbmanager->persist($employee3);
        $dbmanager->persist($employee4);
        $dbmanager->persist($employee5);

        # Create a bunch of managers
        $manager1 = Manager::createUser("randyrhoads", "password123", "Randy Roads", "randyrhoads@test.com", "333-231-4432");
        $manager2 = Manager::createUser("dansmith", "password123", "Dan Smith", null, "883-233-4441");

        $dbmanager->persist($manager1);
        $dbmanager->persist($manager2);

        # Create a bunch of shifts
        $begin = new \DateTime('2017-10-01 08:00:00');
        $end = new \DateTime('2017-11-01 08:00:00');

        # A shift for every day inbetween the above dates
        $interval = new \DateInterval('P1D');
        $daterange = new \DatePeriod($begin, $interval ,$end);

        $managers = [$manager1, $manager2];
        $employees = [$employee1, $employee2, $employee3, $employee4, $employee5, null];

        # Create three shifts for each day with random employees and random managers.
        # The null value in the employee list is to allow for empty shifts
        foreach($daterange as $date){
            $shiftEmployeesKeys = array_rand($employees, 3);
            $shiftManagerKey = array_rand($managers);

            foreach ($shiftEmployeesKeys as $employeeKey) {
                $startDateTime = clone $date;
                $endDateTime = clone $date;
                $endDateTime->modify('+8 hours');

                $shift = new Shift();
                $shift->setStartTime($startDateTime);
                $shift->setEndTime($endDateTime);
                $shift->setBreak(0.5);
                $shift->setEmployee($employees[$employeeKey]);
                $shift->setManager($managers[$shiftManagerKey]);
                $dbmanager->persist($shift);
            }
        }

        $dbmanager->flush();
    }
}
