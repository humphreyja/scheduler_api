<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Users\Employee;

class ScheduleController extends Controller
{
    /**
     * @Route("/schedule", name="schedule")
     */
    public function indexAction(Request $request)
    {
      $em = $this->getDoctrine()->getManager();
      $employees = $em->getRepository(Employee::class)->all();
      $response = new Response($this->container->get('jms_serializer')->serialize($employees, 'json'));
      $response->headers->set('Content-Type', 'application/json');
      return $response;
    }
}
