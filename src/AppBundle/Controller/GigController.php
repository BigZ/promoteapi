<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Gig;
use AppBundle\Form\Type\GigType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use bigz\halapi\Representation\PaginatedRepresentation;
use Symfony\Component\HttpFoundation\Response;

class GigController extends FOSRestController
{
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
     * @param Gig          $gig
     * @param ParamFetcher $paramFetcher
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

        return $form;
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
        $form = $this->createForm(GigType::class, $gig);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($gig);
            $manager->flush();

            return $gig;
        }

        return $form;
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
        $form = $this->createForm(GigType::class, $gig);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($gig);
            $manager->flush();

            return ['status' => 'updated', 'resource_id' => $gig->getId()];
        }

        return $form;
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
        header_register_callback(function () {
            header_remove('Content-type');
            header('Content-Type: application/json');
        });

        return new Response('{}', 204);
    }

    protected function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('AppBundle:Gig');
    }
}
