<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @ApiDoc(
     *     statusCodes = {
     *         403 = "Returned when the credentials are invalid"
     *     },
     *     input="AppBundle\Form\Type\AuthenticatorType",
     *     output="string"
     * )
     *
     * @Route("/token")
     * @Method("POST")
     * @Security("has_role('ROLE_USER')")
     */
    public function getTokenAction()
    {
        return $this->getUser()->getApiKey();
    }
}
