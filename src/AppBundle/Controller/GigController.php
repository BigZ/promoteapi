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

class GigController extends FOSRestController
{
    /**
     * Get all gigs.
     *
     * @ApiDoc(resource=true, filters=PaginatedRepresentation::FILTERS)
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
     * @ApiDoc()
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
     * @Security("is_granted('CREATE')")
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

            return ['status' => 'created', 'resource_id' => $gig->getId()];
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
     * @Security("is_granted('EDIT')")
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

            return ['status' => 'updated', 'resource_id' => $gig->getId()];
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
     * @Security("is_granted('EDIT')")
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
     * @ApiDoc()
     *
     * @Security("is_granted('DELETE')")
     *
     * @param Gig $gig
     *
     * @return array
     */
    public function deleteGigAction(Gig $gig)
    {
        $resourceId = $gig->getId();
        $manager = $this->getDoctrine()->getManager();

        $manager->remove($gig);
        $manager->flush();

        return ['status' => 'deleted', 'resource_id' => $resourceId];
    }

    protected function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('AppBundle:Gig');
    }
}
