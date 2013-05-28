<?php

namespace Sto\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sto\CoreBundle\Entity\Company;
use Sto\CoreBundle\Entity\CompanyGallery;
use Sto\AdminBundle\Form\CompanyType;
use Sto\AdminBundle\Form\CompanyGalleryType;
use Sto\AdminBundle\Form\CompanyManagerType;

/**
 * Company controller.
 *
 * @Route("/company")
 */
class CompanyController extends Controller
{
    /**
     * Lists all Company entities.
     *
     * @Route("/", name="companies")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('StoCoreBundle:Company')
            ->createQueryBuilder('company')
            ->orderBy('company.id')
            ->getQuery()
        ;

        $def_limit = $this->container->getParameter('pagination_default_value');

        $companies = $this->get('knp_paginator')->paginate(
            $query,
            $this->get('request')->query->get('page', 1),
            $this->get('request')->query->get('numItemsPerPage', $def_limit)
        );

        return [
            'companies' => $companies,
        ];
    }

    /**
     * Displays a form to create a new Company entity.
     *
     * @Route("/new", name="company_new")
     * @Template()
     */
    public function newAction()
    {
        return [
            'form' => $this->createForm(new CompanyType, new Company, ['em'=>$em = $this->getDoctrine()->getManager()])->createView(),
        ];
    }

    /**
     * Creates a new Company entity.
     *
     * @Route("/create", name="company_create")
     * @Method("POST")
     * @Template("StoAdminBundle:Company:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $company  = new Company;
        $form = $this->createForm(new CompanyType, $company, ['em'=>$em = $this->getDoctrine()->getManager()]);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $company->setUpdatedAt(new \DateTime());
            $em->persist($company);
            $em->flush();

            return $this->redirect($this->generateUrl('companies'));
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Company entity.
     *
     * @Route("/{id}/edit", name="company_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('StoCoreBundle:Company')->findOneById($id);

        if (!$company) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }
        $editForm = $this->createForm(new CompanyType('edit'), $company, array(
                'em' => $this->getDoctrine()->getManager(),
            ));

        return [
            'company'   => $company,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Edits an existing Company entity.
     *
     * @Route("/{id}/update", name="company_update")
     * @Method("POST")
     * @Template("StoAdminBundle:Company:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('StoCoreBundle:Company')->find($id);

        if (!$company) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        $editForm = $this->createForm(new CompanyType('edit'), $company, array(
                'em' => $this->getDoctrine()->getManager(),
            ));
        $editForm->bind($request);
        if ($editForm->isValid()) {

            $em->persist($company);
            $em->flush();

            return $this->redirect($this->generateUrl('companies'));
        }

        return [
            'company'   => $company,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a Company entity.
     *
     * @Route("/{id}/delete", name="company_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('StoCoreBundle:Company')->findOneById($id);

        if (!$company) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        $em->remove($company);
        $em->flush();

        return $this->redirect($this->generateUrl('companies'));
    }

    /**
     * Edits an existing Company entity.
     *
     * @Route("/{id}/gallery", name="company_gallery")
     * @Template("StoAdminBundle:Company:show_gallery.html.twig")
     * @ParamConverter("company", class="StoCoreBundle:Company")
     */
    public function showGalleryAction($company)
    {
        $companyGallery  = new CompanyGallery;
        $form = $this->createForm(new CompanyGalleryType, $companyGallery);

        return [
            'company' => $company,
            'form' => $form->createView(),
        ];
    }

    /**
     * Edits an existing Company entity.
     *
     * @Route("/{id}/save_image", name="company_save_image")
     * @ParamConverter("company", class="StoCoreBundle:Company")
     */
    public function saveImageAction($company, Request $request)
    {
        $companyGallery  = new CompanyGallery;
        $form = $this->createForm(new CompanyGalleryType, $companyGallery);
        $form->bind($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $companyGallery->setCompany($company);

            $em->persist($companyGallery);
            $em->flush();

            return $this->redirect($this->generateUrl('company_gallery', ['id'=>$company->getId()]));
        }
    }

    /**
     * Edits an existing Company entity.
     *
     * @Route("/{id}/edit_image/{image_id}", name="company_edit_image")
     * @Template("StoAdminBundle:Company:edit_gallery.html.twig")
     */
    public function editImageAction(Request $request, $id, $image_id)
    {
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('StoCoreBundle:Company')->findOneById($id);
        $companyGallery = $em->getRepository('StoCoreBundle:CompanyGallery')->findOneById($image_id);
        $form = $this->createForm(new CompanyGalleryType, $companyGallery);

        return [
            'company' => $company,
            'form' => $form->createView(),
            'image' => $companyGallery,
        ];
    }

    /**
     * Edits an existing Company entity.
     *
     * @Route("/{id}/update_image/{image_id}", name="company_update_image")
     * @Template("StoAdminBundle:Company:edit_gallery.html.twig")
     * @ParamConverter("company", class="StoCoreBundle:Company")
     */
    public function updateImageAction(Request $request, $company, $image_id)
    {
        $em = $this->getDoctrine()->getManager();
        $companyGallery = $em->getRepository('StoCoreBundle:CompanyGallery')->findOneById($image_id);
        $form = $this->createForm(new CompanyGalleryType, $companyGallery);
        $form->bind($request);
        if ($form->isValid()) {
            $em->persist($companyGallery);
            $em->flush();

            return $this->redirect($this->generateUrl('company_gallery', ['id'=>$company->getId()]));
        }

        return [
            'company' => $company,
            'image' => $companyGallery,
            'form' => $form->createView(),
        ];
    }

    /**
     * Deletes a Image entity.
     *
     * @Route("/{id}/delete_image/{image_id}", name="company_delete_image")
     */
    public function deleteImageAction(Request $request, $id, $image_id)
    {
        $em = $this->getDoctrine()->getManager();
        $image = $em->getRepository('StoCoreBundle:CompanyGallery')->findOneById($image_id);

        if (!$image) {
            throw $this->createNotFoundException('Unable to find Image entity.');
        }

        $em->remove($image);
        $em->flush();

        return $this->redirect($this->generateUrl('company_gallery', ['id'=>$id]));
    }

    /**
     * Edits an existing Company entity.
     *
     * @Route("/{id}/managers", name="company_managers")
     * @Template("StoAdminBundle:Company:show_managers.html.twig")
     * @ParamConverter("company", class="StoCoreBundle:Company")
     */
    public function showManagersAction($company)
    {
        $form = $this->createForm(new CompanyManagerType, $company);

        return [
            'company' => $company,
            'form' => $form->createView(),
        ];
    }

    /**
     * Edits an existing Company entity.
     *
     * @Route("/{id}/manaegrs/add", name="company_managers_add")
     * @Method("POST")
     * @Template("StoAdminBundle:Company:edit.html.twig")
     */
    public function addManagersAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('StoCoreBundle:Company')->find($id);

        if (!$company) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        $form = $this->createForm(new CompanyManagerType(), $company);
        $form->bind($request);
        if ($form->isValid()) {

            $em->persist($company);
            $em->flush();

            return $this->redirect($this->generateUrl('company_managers', ['id'=>$id]));
        }

        return [
            'company'   => $company,
            'form' => $form->createView(),
        ];
    }

    /**
     * Deletes a Manager.
     *
     * @Route("/{id}/delete_manager/{user_id}", name="company_delete_manager")
     */
    public function deleteManagerAction(Request $request, $id, $user_id)
    {
        $em = $this->getDoctrine()->getManager();
        $company = $em->getRepository('StoCoreBundle:Company')->findOneById($id);

        if (!$company) {
            throw $this->createNotFoundException('Unable to find Company entity.');
        }

        $user = $em->getRepository('StoUserBundle:User')->findOneById($user_id);

        $company->removeManager($user);

        $em->remove($company);
        $em->flush();

        return $this->redirect($this->generateUrl('company_managers', ['id'=>$id]));
    }
}
