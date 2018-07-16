<?php

/*
 * This file is part of the promote-api package.
 *
 * (c) Bigz
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace App\Controller;

use App\Entity\Label;
use App\Form\Type\LabelType;
use FOS\RestBundle\Controller\ControllerTrait;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use FOS\RestBundle\Controller\Annotations as Rest;
use Halapi\Representation\PaginatedRepresentation;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

/**
 * Class LabelController
 * @author Romain Richard
 */
class LabelController extends Controller
{
    use ControllerTrait;

    /**
     * Get all labels.
     *
     * @SWG\Response(
     *     response=200,
     *     description="Paginated label collection",
     *     @Model(type=PaginatedRepresentation::class)
     * )
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
     * @SWG\Response(response=200, description="Get a label", @Model(type=Label::class))
     * @SWG\Response(response=404, description="Label not found")
     *
     * @param Label $label
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
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Label to add",
     *     required=true,
     *     @Model(type=LabelType::class),
     * )
     * @SWG\Response(response=201, description="Label created", @Model(type=Label::class))
     * )
     * @SWG\Response(response=400, description="Invalid Request")
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

            return $this->view($label, 201);
        }

        return $this->view($form, 400);
    }

    /**
     * Update a label.
     *
     * ApiDoc(
     *  input="App\Form\Type\LabelType",
     *  output="App\Entity\Label",
     *  statusCodes = {
     *     200 = "Label updated",
     *     404 = "Label not found"
     *   }
     * )
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Label to add",
     *     required=true,
     *     @Model(type=LabelType::class),
     * )
     * @SWG\Response(response=200, description="Label updated",
     *     @SWG\Schema(@Model(type=Label::class))
     * )
     * @SWG\Response(response=400, description="Invalid Request")
     * @SWG\Response(response=404, description="Label not found")
     *
     * @param Request $request
     *
     * @Security("is_granted('edit')")
     *
     * @return mixed
     */
    public function putLabelAction(Request $request, Label $label)
    {
        $form = $this->createForm(LabelType::class, $label, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($label);
            $manager->flush();

            return $label;
        }

        return $this->view($form, 400);
    }

    /**
     * Patch a label.
     *
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Label to add",
     *     required=true,
     *     @Model(type=LabelType::class)
     * )
     * @SWG\Response(response=200, description="Label patched", @Model(type=Label::class))
     * @SWG\Response(response=400, description="Invalid Request")
     * @SWG\Response(response=404, description="Label not found")
     *
     * @param Request $request
     *
     * @Security("is_granted('edit')")
     *
     * @return mixed
     */
    public function patchLabelAction(Request $request, Label $label)
    {
        $form = $this->createForm(LabelType::class, $label, ['method' => 'PATCH']);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($label);
            $manager->flush();

            return $label;
        }

        return $this->view($form, 400);
    }

    /**
     * Delete a label.
     *
     * @SWG\Response(response=204, description="Label deleted")
     * @SWG\Response(response=404, description="Label not found")
     *
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
        header_register_callback(
            function () {
                header_remove('Content-type');
            }
        );

        return new Response('', 204);
    }

    /**
     * @return \App\Repository\LabelRepository
     */
    protected function getRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('App:Label');
    }
}
