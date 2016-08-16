<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Label;
use AppBundle\Form\Type\LabelType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use bigz\halapi\Representation\PaginatedRepresentation;

class LabelController extends FOSRestController
{
    /**
     * Get all labels.
     *
     * @ApiDoc(resource=true,filters=PaginatedRepresentation::FILTERS)
     *
     * @param ParamFetcher $paramFetcher
     *
     * @return array
     */
    public function getLabelsAction(ParamFetcher $paramFetcher)
    {
        return $this->getPaginatedRepresentation('label', $paramFetcher);
    }

    /**
     * Get a Label.
     *
     * @Apidoc()
     *
     * @param Label        $label
     * @param ParamFetcher $paramFetcher
     *
     * @return array
     */
    public function getLabelAction(Label $label, ParamFetcher $paramFetcher)
    {
        $this->paramFetcher = $paramFetcher;

        return $this->getResourceRepresentation($label);
    }

    /**
     * Create a new label.
     *
     * @ApiDoc(
     *  input="AppBundle\Form\Type\LabelType",
     *  output="AppBundle\Label"
     * )
     *
     * @param Request $request
     *
     * @Security("is_granted('CREATE')")
     *
     * @return mixed
     */
    public function postLabelAction(Request $request)
    {
        $label = new Label();
        $form = $this->createForm(LabelType::class, $label);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $label->setCreatedBy($this->getUser());
            $manager->persist($label);
            $manager->flush();

            return ['status' => 'created', 'resource_id' => $label->getId()];
        }

        return $form;
    }

    /**
     * Update a label.
     *
     * @ApiDoc(
     *  input="AppBundle\Form\Type\LabelType",
     *  output="AppBundle\Label"
     * )
     *
     * @param Request $request
     *
     * @Security("is_granted('EDIT')")
     *
     * @return mixed
     */
    public function putLabelAction(Request $request, Label $label)
    {
        $form = $this->createForm(LabelType::class, $label);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($label);
            $manager->flush();

            return ['status' => 'updated', 'resource_id' => $label->getId()];
        }

        return $form;
    }

    /**
     * Delete a label.
     *
     * @ApiDoc(
     *  input="AppBundle\Label"
     * )
     *
     * @param Label $label
     *
     * @return array
     */
    public function deleteLabelAction(Label $label)
    {
        $resourceId = $label->getId();
        $manager = $this->getDoctrine()->getManager();

        $manager->remove($label);
        $manager->flush();

        return ['status' => 'deleted', 'resource_id' => $resourceId];
    }

    protected function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('AppBundle:Label');
    }
}
