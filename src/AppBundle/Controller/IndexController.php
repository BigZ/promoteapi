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
use Swagger\Annotations as SWG;
use Symfony\Component\Filesystem\Filesystem;

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
     * @SWG\Response(response=200, description="Swagger schema", @SWG\Schema(type="object"))
     *
     * @return string
     */
    public function indexAction()
    {
        return ($this->get('nelmio_api_doc.generator')->generate()->toArray());
    }
}
