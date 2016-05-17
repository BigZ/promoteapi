<?php


namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Token controller.
 */
class TokenController extends Controller
{
    /**
     * Get token.
     * Protected by login form (username & password as form-data to this page).
     *
     * @ApiDoc()
     *
     * @Route("/token")
     * @Method("POST")
     * @Security("has_role('ROLE_USER')")
     */
    public function getTokenAction()
    {
        return ['token' => $this->getUser()->getApiKey()];
    }
}
