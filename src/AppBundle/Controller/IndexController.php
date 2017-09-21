<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Artist;
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
     * @Method("GET")
     *
     * @return string
     */
    public function indexAction()
    {
        return 'Welcome to the API :)';
    }
}
