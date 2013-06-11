<?php

namespace Sto\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
            return new Response(500, 'Dictionary Not found.');
        }

        $entity->setName($request->get('value'));
        $em->persist($entity);
        $em->flush();

        return new Response(200);
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
            return new Response(500, 'Dictionary Not found.');
        }

        $em->remove($entity);
        $em->flush();

        return new Response(200);
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
            case 'price_level':
                $repository = $em->getRepository('StoCoreBundle:Dictionary\PriceLevel');
                break;
            case 'auto_services':
                $repository = $em->getRepository('StoCoreBundle:Dictionary\AutoServices');
                break;
            default:
                $repository = $em->getRepository('StoCoreBundle:Dictionary\Company');
                break;
        }

        $dictionaries = $repository->createQueryBuilder('dictionary')
            ->orderBy('dictionary.position, dictionary.id, dictionary.parent')
            ->getQuery()
            ->getResult()
        ;

        // var_dump($dictionaries);
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
            case 'price_level':
                $form = $this->createForm(new DictionaryForm\PriceLevel, new DictionaryEntity\PriceLevel);
                break;
            case 'auto_services':
                $form = $this->createForm(new DictionaryForm\AutoServiceType, new DictionaryEntity\AutoServices);
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
        $em = $this->getDoctrine()->getManager();
        switch ($dictionary) {
            case 'company':
                $entity  = new DictionaryEntity\Company;
                $form = $this->createForm(new DictionaryForm\CompanyType, $entity);
                break;
            case 'deal':
                $entity  = new DictionaryEntity\Deal;
                $repository = $em->getRepository('StoCoreBundle:Dictionary\Deal');
                $item = $repository->createQueryBuilder('d')
                    ->orderBy('d.position', 'DESC')
                    ->setMaxResults(1)
                    ->getQuery()
                    ->getResult();
                if (isset($item[0]))
                    $position = $item[0]->getPosition()+1;
                else
                    $position = 1;
                $entity->setPosition($position);
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
            case 'price_level':
                $entity  = new DictionaryEntity\PriceLevel;
                $form = $this->createForm(new DictionaryForm\PriceLevelType, $entity);
                break;
            case 'auto_services':
                $entity  = new DictionaryEntity\AutoServices;
                $form = $this->createForm(new DictionaryForm\AutoServiceType, $entity);
                break;
            default:
                $entity  = new DictionaryEntity\Base;
                $form = $this->createForm(new DictionaryForm\BaseType, $entity);
                break;
        }
        $form->bind($request);

        if ($form->isValid()) {
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
            case 'price_level':
                $entity = $em->getRepository('StoCoreBundle:Dictionary\PriceLevel')->findOneById($id);
                $editForm = $this->createForm(new DictionaryForm\PriceLevelType, $entity);
                break;
            case 'auto_services':
                $entity = $em->getRepository('StoCoreBundle:Dictionary\AutoServices')->findOneById($id);
                $editForm = $this->createForm(new DictionaryForm\AutoServiceType, $entity);
                break;
            default:
                $entity = $em->getRepository('StoCoreBundle:Dictionary\Base')->findOneById($id);
                $editForm = $this->createForm(new DictionaryForm\BaseType, $entity);
                break;
        }

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('dict.errors.unable_2_find'));
        }

        return [
            'entity' => $entity,
            'dictionary' => $dictionary,
            'edit_form' => $editForm->createView(),
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
            case 'price_level':
                $entity = $em->getRepository('StoCoreBundle:Dictionary\PriceLevel')->findOneById($id);
                $editForm = $this->createForm(new DictionaryForm\PriceLevelType, $entity);
                break;
            case 'auto_services':
                $entity = $em->getRepository('StoCoreBundle:Dictionary\AutoServices')->findOneById($id);
                $editForm = $this->createForm(new DictionaryForm\AutoServiceType, $entity);
                break;
            default:
                $entity = $em->getRepository('StoCoreBundle:Dictionary\Base')->findOneById($id);
                $editForm = $this->createForm(new DictionaryForm\BaseType, $entity);
                break;
        }

        if (!$entity) {
            throw $this->createNotFoundException($this->get('translator')->trans('dict.errors.unable_2_find'));
        }

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

    /**
     * Change Position a Dictionary entity.
     *
     * @Route("/change-position", name="change_position_ajax")
     * @Method("POST")
     */
    public function changeDealPositionAjaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('StoCoreBundle:Dictionary\Deal')->findOneById($request->get('id'));

        if (!$entity) {
            return new Response(500, 'Dictionary Not found.');
        }

        $action = $request->get('action');

        $repository = $em->getRepository('StoCoreBundle:Dictionary\Deal');
        $query = $repository->createQueryBuilder('dictionary');

        if ($action == 'up') {
            $query->where('dictionary.position < :current_position')
            ->orderBy('dictionary.position', 'DESC');
        } elseif ($action == 'down') {
            $query->where('dictionary.position > :current_position')
            ->orderBy('dictionary.position', 'ASC');
        } else {
            return new Response(500, 'Action "'.$action.'" is not a position action.');
        }

        $item = $query ->setParameter('current_position', $entity->getPosition())
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;

        if (!$item[0])
            return new Response(500, 'Dictionary Not found.');

        $buf_position = $entity->getPosition();
        $entity->setPosition($item[0]->getPosition());

        $em->persist($entity);
        $em->flush();

        $query = $repository->createQueryBuilder('StoCoreBundle:Dictionary\Deal');
        $query->update('StoCoreBundle:Dictionary\Deal','d')
            ->set('d.position', '?1')
            ->where('d.id = ?2')
            ->setParameter(1, $buf_position)
            ->setParameter(2, $item[0]->getId())
            ->getQuery()
            ->execute();

        return new Response($request->get('id'));
    }
}
