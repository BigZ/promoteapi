<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Gig;
use AppBundle\Form\Type\GigType;
use FOS\RestBundle\Request\ParamFetcher;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;
use Hateoas\Representation\RouteAwareRepresentation;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Hateoas\Configuration\Annotation as Hateoas;

class GigController extends HALController
{
    /**
     * @param ParamFetcher $paramFetcher
     * @return array
     */
    public function getGigsAction(ParamFetcher $paramFetcher)
    {
        return $this->getPaginatedRepresentation('gig', $paramFetcher);
    }

    /**
     * @param Gig $gig
     * @param ParamFetcher $paramFetcher
     * @return array
     */
    public function getGigAction(Gig $gig, ParamFetcher $paramFetcher)
    {
        $this->paramFetcher = $paramFetcher;
        return $this->getResourceRepresentation($gig);
    }

    /**
     * @param Request $request
     *
     * @Security("is_granted('CREATE')")
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
     * @param Request $request
     *
     * @Security("is_granted('EDIT')")
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
     * @Route(name="gig_delete")
     * @param Gig $gig
     * @return array
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
