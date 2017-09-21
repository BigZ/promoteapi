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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class IndexController.
 *
 * @Route("/")
 */
class IndexController extends Controller
{
    /**
     * @Route(name="index", path="/")
     *
     * @Method("GET")
     *
     * @return string
     */
    public function indexAction()
    {
        return 'Welcome to the API :)';
    }
}
