<?php

namespace Sto\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Responce,
    Symfony\Component\Translation\Translator,
    Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sto\AdminBundle\Form\Dictionary as DictionaryForm;
use Sto\CoreBundle\Entity\Dictionary as DictionaryEntity;

/**
 * Dictionary controller.
 *
 * @Route("/dictionary")
 */
class DictionaryController extends Controller
{
    /**
     * Lists all Dictionary entities.
     *
     * @Route("/", name="dictionary")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

    public function checkAuthAjax()
    {
        if (true === $this->get('security.context')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY')) {
            throw new AccessDeniedException();
        }
    }

    /**
     * Lists all Dictionary entities.
     *
     * @Route("/change_field", name="dictionary_change_field")
     */
    public function changeFieldAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StoCoreBundle:Dictionary\Base')->findOneById($request->get('pk'));

        if (!$entity) {
            return new Responce(500, 'Dictionary Not found.');
        }

        $entity->setName($request->get('value'));
        $em->persist($entity);
        $em->flush();

        return new Responce(200);
    }

    /**
     * Deletes a Dictionary entity.
     *
     * @Route("/delete_ajax", name="dictionary_delete_ajax")
     * @Method("POST")
     */
    public function deleteAjaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StoCoreBundle:Dictionary\Base')->findOneById($request->get('id'));

        if (!$entity) {
            return new Responce(500, 'Dictionary Not found.');
        }

        $em->remove($entity);
        $em->flush();

        return new Responce(200);
    }

    /**
     * Lists all Dictionary entities.
     *
     * @Route("/content", name="dictionary_content")
     * @Template()
     */
    public function contentAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        switch ($request->get('dictionary')) {
            case 'company':
                $repository = $em->getRepository('StoCoreBundle:Dictionary\Company');
                break;
            case 'deal':
                $repository = $em->getRepository('StoCoreBundle:Dictionary\Deal');
                break;
            case 'service':
                $repository = $em->getRepository('StoCoreBundle:Dictionary\AdditionalService');
                break;
            case 'work':
                $repository = $em->getRepository('StoCoreBundle:Dictionary\Work');
                break;
            case 'currency':
                $repository = $em->getRepository('StoCoreBundle:Dictionary\Currency');
                break;
            case 'country':
                $repository = $em->getRepository('StoCoreBundle:Dictionary\Country');
                break;
            default:
                $repository = $em->getRepository('StoCoreBundle:Dictionary\Company');
                break;
        }

        $dictionaries = $repository->createQueryBuilder('dictionary')
            ->orderBy('dictionary.id, dictionary.parent')
            ->getQuery()
            ->getResult()
        ;

        return [
            'dictionary' => $request->get('dictionary') ? $request->get('dictionary') : 'company',
            'dictionaries' => $dictionaries,
        ];
    }

    /**
     * Displays a form to create a new Dictionary entity.
     *
     * @Route("/new/{dictionary}", name="dictionary_new")
     * @Template()
     */
    public function newAction($dictionary)
    {
        switch ($dictionary) {
            case 'company':
                $form = $this->createForm(new DictionaryForm\CompanyType, new DictionaryEntity\Company);
                break;
            case 'deal':
                $form = $this->createForm(new DictionaryForm\DealType, new DictionaryEntity\Deal);
                break;
            case 'service':
                $form = $this->createForm(new DictionaryForm\ServiceType, new DictionaryEntity\AdditionalService);
                break;
            case 'work':
                $form = $this->createForm(new DictionaryForm\WorkType, new DictionaryEntity\Work);
                break;
            case 'currency':
                $form = $this->createForm(new DictionaryForm\CurrencyType, new DictionaryEntity\Currency);
                break;
            case 'country':
                $form = $this->createForm(new DictionaryForm\CountryType, new DictionaryEntity\Country);
                break;
            default:
                $form = $this->createForm(new DictionaryForm\BaseType, new DictionaryEntity\Base);
                break;
        }

        return [
            'dictionary' => $dictionary,
            'form' => $form->createView(),
        ];
    }

    /**
     * Creates a new Dictionary entity.
     *
     * @Route("/{dictionary}/create", name="dictionary_create")
     * @Method("POST")
     * @Template("StoCoreBundle:Dictionary:new.html.twig")
     */
    public function createAction(Request $request, $dictionary)
    {
        switch ($dictionary) {
            case 'company':
                $entity  = new DictionaryEntity\Company;
                $form = $this->createForm(new DictionaryForm\CompanyType, $entity);
                break;
            case 'deal':
                $entity  = new DictionaryEntity\Deal;
                $form = $this->createForm(new DictionaryForm\DealType, $entity);
                break;
            case 'service':
                $entity  = new DictionaryEntity\AdditionalService;
                $form = $this->createForm(new DictionaryForm\ServiceType, $entity);
                break;
            case 'work':
                $entity  = new DictionaryEntity\Work;
                $form = $this->createForm(new DictionaryForm\WorkType, $entity);
                break;
            case 'currency':
                $entity  = new DictionaryEntity\Currency;
                $form = $this->createForm(new DictionaryForm\CurrencyType, $entity);
                break;
            case 'country':
                $entity  = new DictionaryEntity\Country;
                $form = $this->createForm(new DictionaryForm\CountryType, $entity);
                break;
            default:
                $entity  = new DictionaryEntity\Base;
                $form = $this->createForm(new DictionaryForm\BaseType, $entity);
                break;
        }
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('dictionary'));
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Dictionary entity.
     *
     * @Route("/{id}/{dictionary}/edit", name="dictionary_edit")
     * @Template()
     */
    public function editAction($id, $dictionary)
    {
        $em = $this->getDoctrine()->getManager();
        switch ($dictionary) {
            case 'company':
                $entity = $em->getRepository('StoCoreBundle:Dictionary\Company')->findOneById($id);
                $editForm = $this->createForm(new DictionaryForm\CompanyType, $entity);
                break;
            case 'deal':
                $entity = $em->getRepository('StoCoreBundle:Dictionary\Deal')->findOneById($id);
                $editForm = $this->createForm(new DictionaryForm\DealType, $entity);
                break;
            case 'service':
                $entity = $em->getRepository('StoCoreBundle:Dictionary\AdditionalService')->findOneById($id);
                $editForm = $this->createForm(new DictionaryForm\ServiceType, $entity);
                break;
            case 'work':
                $entity = $em->getRepository('StoCoreBundle:Dictionary\Work')->findOneById($id);
                $editForm = $this->createForm(new DictionaryForm\WorkType, $entity);
                break;
            case 'currency':
                $entity = $em->getRepository('StoCoreBundle:Dictionary\Currency')->findOneById($id);
                $editForm = $this->createForm(new DictionaryForm\CurrencyType, $entity);
                break;
            case 'country':
                $entity = $em->getRepository('StoCoreBundle:Dictionary\Country')->findOneById($id);
                $editForm = $this->createForm(new DictionaryForm\CountryType, $entity);
                break;
            default:
                $entity = $em->getRepository('StoCoreBundle:Dictionary\Base')->findOneById($id);
                $editForm = $this->createForm(new DictionaryForm\BaseType, $entity);
                break;
        }

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('dict.errors.unable_2_find'));
        }

        $deleteForm = $this->createDeleteForm($id);

        return [
            'entity' => $entity,
            'dictionary' => $dictionary,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Edits an existing Dictionary entity.
     *
     * @Route("/{id}/{dictionary}/update", name="dictionary_update")
     * @Method("POST")
     * @Template("StoAdminBundle:Dictionary:edit.html.twig")
     */
    public function updateAction(Request $request, $id, $dictionary)
    {
        $em = $this->getDoctrine()->getManager();
        switch ($dictionary) {
            case 'company':
                $entity = $em->getRepository('StoCoreBundle:Dictionary\Company')->findOneById($id);
                $editForm = $this->createForm(new DictionaryForm\CompanyType, $entity);
                break;
            case 'deal':
                $entity = $em->getRepository('StoCoreBundle:Dictionary\Deal')->findOneById($id);
                $editForm = $this->createForm(new DictionaryForm\DealType, $entity);
                break;
            case 'service':
                $entity = $em->getRepository('StoCoreBundle:Dictionary\AdditionalService')->findOneById($id);
                $editForm = $this->createForm(new DictionaryForm\ServiceType, $entity);
                break;
            case 'work':
                $entity = $em->getRepository('StoCoreBundle:Dictionary\Work')->findOneById($id);
                $editForm = $this->createForm(new DictionaryForm\WorkType, $entity);
                break;
            case 'currency':
                $entity = $em->getRepository('StoCoreBundle:Dictionary\Currency')->findOneById($id);
                $editForm = $this->createForm(new DictionaryForm\CurrencyType, $entity);
                break;
            case 'country':
                $entity = $em->getRepository('StoCoreBundle:Dictionary\Country')->findOneById($id);
                $editForm = $this->createForm(new DictionaryForm\CountryType, $entity);
                break;
            default:
                $entity = $em->getRepository('StoCoreBundle:Dictionary\Base')->findOneById($id);
                $editForm = $this->createForm(new DictionaryForm\BaseType, $entity);
                break;
        }

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('dict.errors.unable_2_find'));
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('dictionary'));
        }

        return [
            'entity' => $entity,
            'dictionary' => $dictionary,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a Dictionary entity.
     *
     * @Route("/{id}/delete", name="dictionary_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StoCoreBundle:Dictionary\Base')->findOneById($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('dict.errors.unable_2_find'));
        }

        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('dictionary'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(['id' => $id])
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
