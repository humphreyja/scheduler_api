<?php

namespace AppBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Controller\Api\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Users\Employee;
use AppBundle\Entity\Shift;

/**
 * Employee controller responsed to requests relating to employee specific items.
 * This includes listing an employee's work history, as well as listing all employees.
 */
class EmployeeController extends BaseController
{
    /**
     * @Route("/api/history", name="employee_profile")
     * @Method({"GET"})
     * @Security("has_role('ROLE_EMPLOYEE')")
     */
    public function showAction(Request $request)
    {
        $employee = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $history = $em->getRepository(Employee::class)->getWorkHistory($employee->getId());

        // If no history is returned, then default to an empty list
        if (empty($history)) {
            $history = [];
        }

        $groups = [
            'Default'
        ];
        return $this->createStandardResponse($history, $groups);
    }

    /**
     * @Route("/api/employees", name="employees")
     * @Method({"GET"})
     * @Security("has_role('ROLE_MANAGER')")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $employees = $em->getRepository(Employee::class)->findAll();

        $groups = [
            'Default'
        ];
        return $this->createStandardResponse($employees, $groups);
    }
}
