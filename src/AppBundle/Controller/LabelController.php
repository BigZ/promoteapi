<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Label;
use AppBundle\Form\Type\LabelType;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use FOS\RestBundle\Controller\Annotations as Rest;

class LabelController extends HALController
{
    /**
     * @param ParamFetcher $paramFetcher
     * @return array
     */
    public function getLabelsAction(ParamFetcher $paramFetcher)
    {
        return $this->getPaginatedRepresentation('label', $paramFetcher);
    }

    /**
     * @param Label $label
     * @param ParamFetcher $paramFetcher
     * @return array
     */
    public function getLabelAction(Label $label, ParamFetcher $paramFetcher)
    {
        $this->paramFetcher = $paramFetcher;
        return $this->getResourceRepresentation($label);
    }

    /**
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
     * @param Label $label
     * @return array
     */
    public function deleteLabelAction(Label $label)
    {
        $id = $label->getId();
        $manager = $this->getDoctrine()->getManager();

        $manager->remove($label);
        $manager->flush();

        return ['status' => 'deleted', 'resource_id' => $id];
    }

    protected function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('AppBundle:Label');
    }
}
