<?php

/*
 * This file is part of the promote-api package.
 *
 * (c) Bigz
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Swagger\Annotations as SWG;

/**
 * Token controller.
 */
class TokenController extends Controller
{
    /**
     * Get token.
     * Protected by login form (username & password as form-data to this page).
     *
     * @SWG\Response(response=200, description="Auth granted")
     * @SWG\Response(response=403, description="Invalid credentials")
     * @Route("/token")
     *
     * @Method("POST")
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @return string
     */
    public function getTokenAction()
    {
        return $this->getUser()->getApiKey();
    }
}
