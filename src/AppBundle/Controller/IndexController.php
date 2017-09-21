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
        $meta = $this->get('doctrine.orm.entity_manager')->getClassMetadata(get_class(new Artist()));
        dump($meta->getAssociationTargetClass('labels'));
        die;
        return 'Welcome to the API :)';
    }
}
