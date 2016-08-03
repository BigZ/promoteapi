<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Gig;
use AppBundle\Form\Type\GigType;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class GigController extends HALController
{
    /**
     * Get all gigs.
     *
     * @ApiDoc(resource=true,filters={
     *      {"name"="page", "dataType"="integer"},
     *      {"name"="limit", "dataType"="integer"},
     *      {"name"="sorting", "dataType"="array", "pattern"="[field]=(asc|desc)"},
     *      {"name"="filtervalue", "dataType"="array", "pattern"="[field]=value"},
     *      {"name"="filteroperator", "dataType"="array", "pattern"="[field]=(<|>|<=|>=|=|!=)"}
     *  })
     *
     * @param  ParamFetcher $paramFetcher
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
     * @param    Gig          $gig
     * @param    ParamFetcher $paramFetcher
     * @return   array
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
     *  output="AppBundle\Gig"
     * )
     *
     * @Security("is_granted('CREATE')")
     *
     * @param  Request $request
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
     *  output="AppBundle\Gig"
     * )
     *
     * @Security("is_granted('EDIT')")
     *
     * @param  Request $request
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
     *  output="AppBundle\Gig"
     * )
     *
     * @Security("is_granted('EDIT')")
     *
     * @param  Request $request
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
     * @ApiDoc(
     *  input="AppBundle\Entity\Gig"
     * )
     *
     * @Security("is_granted('DELETE')")
     * @param                            Gig $gig
     * @return                           array
     */
    public function deleteGigAction(Gig $gig)
    {
        $id = $gig->getId();
        $manager = $this->getDoctrine()->getManager();

        $manager->remove($gig);
        $manager->flush();

        return ['status' => 'deleted', 'resource_id' => $id];
    }

    protected function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('AppBundle:Gig');
    }
}
