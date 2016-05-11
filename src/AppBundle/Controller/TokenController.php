<?php


namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Token controller.
 */
class TokenController extends Controller
{
    /**
     * Get token
     *
     * @Route("/token")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function getTokenAction()
    {
        return $this->getUser()->getApiKey();
    }

}