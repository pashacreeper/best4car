<?php

namespace Sto\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sto\CoreBundle\Entity\Company,
    Sto\AdminBundle\Form\CompanyType;

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
            'form' => $this->createForm(new CompanyType, new Company)->createView(),
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
        $form = $this->createForm(new CompanyType, $company);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
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

        $editForm = $this->createForm(new CompanyType, $company);

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

        $editForm = $this->createForm(new CompanyType, $company);
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
     * Finds and displays a Company entity.
     *
     * @Route("/{id}", name="company_show")
     * @Method("GET")
     * @Template()
     * @ParamConverter("company", class="StoCoreBundle:Company")
     */
    public function showAction($company)
    {
        return [
            'company' => $company,
        ];
    }
}
