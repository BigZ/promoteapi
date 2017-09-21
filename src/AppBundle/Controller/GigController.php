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

use AppBundle\Entity\Gig;
use AppBundle\Form\Type\GigType;
use FOS\RestBundle\Controller\ControllerTrait;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Halapi\Representation\PaginatedRepresentation;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GigController
 * @author Romain Richard
 */
class GigController extends Controller
{
    use ControllerTrait;

    /**
     * Get all gigs.
     *
     * @ApiDoc(resource=true, filters=PaginatedRepresentation::FILTERS,
     *     output="bigz\halapi\Representation\PaginatedRepresentation",
     *     statusCodes = {
     *         200 = "Returns the paginated artists collection",
     *         400 = "Error"
     *     })
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return array
     */
    public function getGigsAction(ParamFetcher $paramFetcher)
    {
        return $this->get('bigz_halapi.pagination_factory')->getRepresentation(Gig::class, $paramFetcher);
    }

    /**
     * Get a gig.
     *
     * @ApiDoc(output="AppBundle\Entity\Gig")
     *
     * @param Gig $gig
     *
     * @return array
     */
    public function getGigAction(Gig $gig)
    {
        return $gig;
    }

    /**
     * Create a new Gig.
     *
     * @ApiDoc(
     *  input="AppBundle\Form\Type\GigType",
     *  output="AppBundle\Entity\Gig"
     * )
     *
     * @Security("is_granted('create')")
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function postGigAction(Request $request)
    {
        $gig = new Gig();
        $form = $this->createForm(GigType::class, $gig);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $gig->setCreatedBy($this->getUser());
            $manager->persist($gig);
            $manager->flush();

            return $gig;
        }

        return $this->view($form, 400);
    }

    /**
     * Modify an existing Gig.
     *
     * @ApiDoc(
     *  input="AppBundle\Form\Type\GigType",
     *  output="AppBundle\Entity\Gig"
     * )
     *
     * @Security("is_granted('edit')")
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function putGigAction(Request $request, Gig $gig)
    {
        $form = $this->createForm(GigType::class, $gig, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($gig);
            $manager->flush();

            return $gig;
        }

        return $this->view($form, 400);
    }

    /**
     * Patch an existing Gig.
     *
     * @ApiDoc(
     *  input="AppBundle\Form\Type\GigType",
     *  output="AppBundle\Entity\Gig"
     * )
     *
     * @Security("is_granted('edit')")
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function patchGigAction(Request $request, Gig $gig)
    {
        $form = $this->createForm(GigType::class, $gig, ['method' => 'PATCH']);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($gig);
            $manager->flush();

            return $gig;
        }

        return $this->view($form, 400);
    }

    /**
     * Delete an existing Gig.
     *
     * @ApiDoc(statusCodes = {
     *     204 = "Gig deleted",
     *     404 = "Gig not found"
     *   })
     *
     * @Security("is_granted('delete')")
     *
     * @param Gig $gig
     *
     * @return array
     */
    public function deleteGigAction(Gig $gig)
    {
        $manager = $this->getDoctrine()->getManager();

        $manager->remove($gig);
        $manager->flush();

        // Dirty Fix for php webserver
        // see https://github.com/symfony/symfony/issues/12744
        header_register_callback(
            function () {
                header_remove('Content-type');
                header('Content-Type: application/json');
            }
        );

        return new Response('{}', 204);
    }

    /**
     * @return \AppBundle\Repository\GigRepository
     */
    protected function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('AppBundle:Gig');
    }
}
