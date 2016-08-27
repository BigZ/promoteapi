<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Label;
use AppBundle\Form\Type\LabelType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations as Rest;
use bigz\halapi\Representation\PaginatedRepresentation;
use Symfony\Component\HttpFoundation\Response;

class LabelController extends FOSRestController
{
    /**
     * Get all labels.
     *
     * @ApiDoc(
     *     resource=true,
     *     filters=PaginatedRepresentation::FILTERS,
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
    public function getLabelsAction(ParamFetcher $paramFetcher)
    {
        return $this->get('bigz_halapi.pagination_factory')->getRepresentation(Label::class, $paramFetcher);
    }

    /**
     * Get a Label.
     *
     * @Apidoc(output="AppBundle\Entity\Label", statusCodes = {
     *         200 = "Returns the artist",
     *         404 = "Not found"
     *     })
     *
     * @param Label        $label
     * @param ParamFetcher $paramFetcher
     *
     * @return array
     */
    public function getLabelAction(Label $label)
    {
        return $label;
    }

    /**
     * Create a new label.
     *
     * @ApiDoc(
     *  input="AppBundle\Form\Type\LabelType",
     *  output="AppBundle\Entity\Label"
     * )
     *
     * @param Request $request
     *
     * @Security("is_granted('create')")
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

            return $label;
        }

        return $form;
    }

    /**
     * Update a label.
     *
     * @ApiDoc(
     *  input="AppBundle\Form\Type\LabelType",
     *  output="AppBundle\Entity\Label"
     * )
     *
     * @param Request $request
     *
     * @Security("is_granted('edit')")
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

            return $label->getId();
        }

        return $form;
    }

    /**
     * Delete a label.
     *
     * @ApiDoc(statusCodes = {
     *     204 = "Label deleted",
     *     404 = "Label not found"
     *   }
     * )
     * @Security("is_granted('delete')")
     *
     * @param Label $label
     *
     * @Rest\View(statusCode=204)
     *
     * @return array
     */
    public function deleteLabelAction(Label $label)
    {
        $manager = $this->getDoctrine()->getManager();

        $manager->remove($label);
        $manager->flush();

        // Dirty Fix for php webserver
        // see https://github.com/symfony/symfony/issues/12744
        header_register_callback(function () {
            header_remove('Content-type');
        });

        return new Response('', 204);
    }

    protected function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('AppBundle:Label');
    }
}
