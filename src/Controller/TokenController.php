<?php

/*
 * This file is part of the promote-api package.
 *
 * (c) Bigz
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace App\Controller;

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
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Authentication credentials",
     *     required=true,
     *     @SWG\Schema(
     *           required={"username", "password"},
     *           @SWG\Property(property="username", type="string"),
     *           @SWG\Property(property="password", type="string")
     *     )
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Auth granted",
     *     @SWG\Schema(
     *           @SWG\Property(property="apiKey", type="string")
     *     ))
     * @SWG\Response(response=403, description="Invalid credentials")
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @return array<string, string>
     */
    public function postTokenAction()
    {
        return ['apiKey' => $this->getUser()->getApiKey()];
    }
}
