<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class TokenController extends Controller
{

  /**
   * @Route("/api/tokens")
   * @Method("POST")
   */
  public function newToken() {
    return Response("TOKEN");
  }
}
