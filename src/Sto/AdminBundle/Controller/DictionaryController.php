<?php

namespace Sto\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\Translation\Translator,
    Symfony\Component\HttpFoundation\Responce,
    Symfony\Component\Security\Core\Exception\AccessDeniedException,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sto\CoreBundle\Entity\Dictionary,
    Sto\AdminBundle\Form\DictionaryType;

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
        $entity = $em->getRepository('StoCoreBundle:Dictionary')->findOneById($request->get('pk'));

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
        $entity = $em->getRepository('StoCoreBundle:Dictionary')->findOneById($request->get('id'));

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
                $repository = $em->getRepository('StoCoreBundle:DictionaryCompanyType');
                break;
            case 'service':
                $repository = $em->getRepository('StoCoreBundle:DictionaryAdditionalService');
                break;
            case 'work':
                $repository = $em->getRepository('StoCoreBundle:DictionaryWork');
                break;
            case 'currency':
                $repository = $em->getRepository('StoCoreBundle:DictionaryCurrency');
                break;
            case 'country':
                $repository = $em->getRepository('StoCoreBundle:DictionaryCountry');
                break;
            case 'city':
                $repository = $em->getRepository('StoCoreBundle:DictionaryCity');
                break;
            default:
                $repository = $em->getRepository('StoCoreBundle:DictionaryCompanyType');
                break;
        }

        $dictionaries = $repository->createQueryBuilder('dictionary')
            ->orderBy('dictionary.id')
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
     * @Route("/new", name="dictionary_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Dictionary;
        $form = $this->createForm(new DictionaryType, $entity);

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * Creates a new Dictionary entity.
     *
     * @Route("/create", name="dictionary_create")
     * @Method("POST")
     * @Template("StoCoreBundle:Dictionary:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Dictionary();
        $form = $this->createForm(new DictionaryType(), $entity);
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
     * @Route("/{id}/edit", name="dictionary_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StoCoreBundle:Dictionary')->findOneById($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('dict.errors.unable_2_find'));
        }

        $editForm = $this->createForm(new DictionaryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Edits an existing Dictionary entity.
     *
     * @Route("/{id}/update", name="dictionary_update")
     * @Method("POST")
     * @Template("StoCoreBundle:Dictionary:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StoCoreBundle:Dictionary')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('dict.errors.unable_2_find'));
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new DictionaryType, $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('dictionary'));
        }

        return [
            'entity' => $entity,
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
        $entity = $em->getRepository('StoCoreBundle:Dictionary')->findOneById($id);

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
