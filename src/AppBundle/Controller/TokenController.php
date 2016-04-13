<?php


namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\Post;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Token controller.
 *
 * @Route("/token")
 */
class TokenController extends Controller
{
    /**
     * Get token
     *
     * @Post("/")
     */
    public function getTokenAction()
    {
        return $this->getUser()->getApiKey();
    }

}