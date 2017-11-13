<?php

namespace AppBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Controller\Api\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Users\Employee;
use AppBundle\Entity\Users\Manager;
use AppBundle\Entity\Shift;
use AppBundle\Form\ShiftType;

/**
 * Shift controller responsed to requests relating to shift specific items.
 * This includes listing all shifts (or shifts available to employee), scheduling shifts, and updating shifts.
 */
class ShiftController extends BaseController
{
    /**
     * @Route("/api/shifts/new", name="create_shift")
     * @Method({"POST"})
     * @Security("has_role('ROLE_MANAGER')")
     */
    public function scheduleShiftAction(Request $request)
    {
        $user = $this->getUser();
        $shift = new Shift();

        // Generate a form (default options weren't working...)
        $form = $this->createForm(ShiftType::class, $shift, ['csrf_protection' => false, 'allow_extra_fields' => true]);
        $data = $this->getRequestBody($request);
        
        // Check if the manager id is present in the body, otherwise set it to the current manager.
        if (!array_key_exists("manager_id", $data)) {
            $data["manager_id"] = $user->getId();
        }

        // Submit the data and validate.
        $form->submit($data);
        if ($form->isValid())
        {
            // Store valid data.
            $em = $this->getDoctrine()->getManager();
            $em->persist($shift);
            $em->flush();
            return $this->createCreateSuccessResponse('api/shifts/'.$shift->getId());
        }else{
            return $this->createErrorResponse($form->getErrors());
        }
    }

    /**
     * @Route("/api/shifts/edit/{id}", name="change_shift")
     * @Method({"PUT"})
     * @Security("has_role('ROLE_MANAGER')")
     */
    public function rescheduleShiftAction($id, Request $request)
    {

        // Find the requested shift or render a not found.
        $em = $this->getDoctrine()->getManager();
        $shift = $em->getRepository(Shift::class)->find($id);

        if (empty($shift)) {
            return $this->createNotFoundErrorResponse();
        }

        // Create a reschedule form which only processes start and end date for shifts.
        $data = $this->getRequestBody($request);

        // Accepts changes to start and end times and break.
        foreach ($data as $key => $value) {
            switch ($key) {
                case 'start_time':
                    $parsedStartTime = \DateTime::createFromFormat("D, d M Y H:i:s O", $value);
                    if ($parsedStartTime) {
                        $shift->setStartTime($parsedStartTime);
                        break;
                    } else {
                        return $this->createErrorResponse("Invalid format for start_time");
                    }
                case 'end_time':
                    $parsedEndTime = \DateTime::createFromFormat("D, d M Y H:i:s O", $value);
                    if ($parsedEndTime) {
                        $shift->setEndTime($parsedEndTime);
                        break;
                    } else {
                        return $this->createErrorResponse("Invalid format for end_time");
                    }
                case 'break':
                    $shift->setBreak(floatval($value));
                    break;
            }
        }

        // Validate the object before storing.
        $validator = $this->get('validator');
        $errors = $validator->validate($shift);

        // Check if there are validation errors
        if (count($errors) > 0) {
           return $this->createErrorResponse($errors);
        }

        $em->persist($shift);
        $em->flush();
        return $this->createUpdateSuccessResponse('api/shifts/'.$shift->getId());
    }

    /**
     * @Route("/api/shifts/{shiftId}/assign/{employeeId}", name="assign_shift")
     * @Method({"PUT"})
     * @Security("has_role('ROLE_MANAGER')")
     */
    public function assignShiftAction($shiftId, $employeeId, Request $request)
    {
        // Find the requested shift or render a not found.
        $em = $this->getDoctrine()->getManager();
        $shift = $em->getRepository(Shift::class)->find($shiftId);

        if (empty($shift)) {
            return $this->createNotFoundErrorResponse();
        }

        $employee = $em->getRepository(Employee::class)->find($employeeId);

        if (empty($employee)) {
            return $this->createNotFoundErrorResponse();
        }

        $shift->setEmployee($employee);

        // Validate the object before storing.
        $validator = $this->get('validator');
        $errors = $validator->validate($shift);

        // Check if there are validation errors
        if (count($errors) > 0) {
           return $this->createErrorResponse($errors);
        }

        $em->persist($shift);
        $em->flush();
        return $this->createUpdateSuccessResponse('api/shifts/'.$shift->getId());
    }

    /**
     * This action will return a list of shifts.  If the current user is an employee,
     * the shifts only apply to them.  If the current user is a manager, then all the shifts
     * are returned and allows for the manager to scope the shifts by date range.
     *
     * @Route("/api/shifts", name="shifts")
     * @Method({"GET"})
     */
    public function listShiftsAction(Request $request)
    {
        $user = $this->getUser();

        if ($user instanceof Employee) {
            return $this->listEmployeeSchedule($request);
        } elseif ($user instanceof Manager) {
            return $this->listManagerSchedule($request);
        } else {
            return $this->createErrorResponse();
        }
    }

    /**
     * @Route("/api/shifts/{id}", name="shift")
     * @Method({"GET"})
     * @Security("has_role('ROLE_EMPLOYEE')")
     */
    public function showAction($id, Request $request)
    {

        // Get the current employee and try to find the shift based on the employee id and shift id.
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $shift = $em->getRepository(Shift::class)->findOneBy(['employeeId' => $user->getId(), 'id' => $id]);

        if (empty($shift))
        {
            return $this->createNotFoundErrorResponse();
        }

        // Get employees that are also working during the selected shift.
        $employees = $em->getRepository(Employee::class)->workingDuringShift($shift->getId(), $user->getId());

        // Set the virtual attribute on the shift for the empolyees working during the shift.
        // Then add the `same_shift_group` to the serializer so the API results show the added data.
        $shift->setSameShiftEmployees($employees);

        // Show default data, manager related data, and employees working during shift data.
        $groups = [
            'Default',
            'manager_details_group',
            'same_shift_group'
        ];
        return $this->createStandardResponse($shift, $groups);
    }

    /**
     * Auxiliary function to `listShiftsAction` scoped only to employees.
     * Returns a list of shifts related only to the current employee.
     */
    private function listEmployeeSchedule($request)
    {
        $employee = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $shifts = $em->getRepository(Employee::class)->getAllShifts($employee->getId());

        // Show default data, manager data, and see if the shift is assigned to employee or available.
        $groups = [
            'Default',
            'manager_details_group',
            'check_assignment_group'
        ];
        return $this->createStandardResponse($shifts, $groups);
    }

    /**
     * Auxiliary function to `listShiftsAction` scoped only to managers.
     * Returns a list of all shifts with ability to scope by date range.
     */
    private function listManagerSchedule($request)
    {
        $user = $this->getUser();
        $data = $this->getRequestBody($request);

        $startRange = \DateTime::createFromFormat("D, d M Y H:i:s O", $request->query->get('start_range'));
        $endRange = \DateTime::createFromFormat("D, d M Y H:i:s O", $request->query->get('end_range'));

        $em = $this->getDoctrine()->getManager();
        $shifts = $em->getRepository(Shift::class)->findAllByRange($startRange, $endRange);


        // Show default data, manager data, and employee data.
        $groups = [
            'Default',
            'manager_details_group',
            'employee_details_group'
        ];
        return $this->createStandardResponse($shifts, $groups);
    }
}
