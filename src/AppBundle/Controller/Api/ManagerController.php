<?php

namespace AppBundle\Controller\Api;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Controller\Api\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Users\Manager;
use AppBundle\Entity\Shift;

/**
 * Manager controller responsed to requests relating to manager specific items.
 * This includes listing all managers.
 */
class ManagerController extends BaseController
{
    /**
     * @Route("/api/managers", name="managers")
     * @Method({"GET"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $managers = $em->getRepository(Manager::class)->findAll();

        $groups = [
            'Default'
        ];
        return $this->createStandardResponse($managers, $groups);
    }
}
