<?php

namespace InteractiveValley\PublicacionesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use InteractiveValley\PublicacionesBundle\Entity\Categoria;
use InteractiveValley\PublicacionesBundle\Form\CategoriaType;

/**
 * Categoria controller.
 *
 * @Route("/backend/categorias")
 */
class CategoriaController extends Controller
{

    /**
     * Lists all Categoria entities.
     *
     * @Route("/", name="categorias")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PublicacionesBundle:Categoria')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Categoria entity.
     *
     * @Route("/", name="categorias_create")
     * @Method("POST")
     * @Template("PublicacionesBundle:Categoria:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Categoria();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('categorias_show', array('id' => $entity->getId())));
        }
        $RpsStms = $this->container->get('rpsstms');
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'errores' => $RpsStms->getErrorMessages($form),
        );
    }

    /**
     * Creates a form to create a Categoria entity.
     *
     * @param Categoria $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Categoria $entity)
    {
        $form = $this->createForm(new CategoriaType(), $entity, array(
            'action' => $this->generateUrl('categorias_create'),
            'method' => 'POST',
        ));

        //$form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Categoria entity.
     *
     * @Route("/new", name="categorias_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Categoria();
        $max = $this->getDoctrine()->getRepository('PublicacionesBundle:Categoria')
                                   ->getMaxPosicion();
        if (!is_null($max)) {
            $entity->setPosition($max + 1);
        } else {
            $entity->setPosition(1);
        }
        $form   = $this->createCreateForm($entity);
        $RpsStms = $this->container->get('rpsstms');
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'errores' => $RpsStms->getErrorMessages($form),
        );
    }

    /**
     * Finds and displays a Categoria entity.
     *
     * @Route("/{id}", name="categorias_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PublicacionesBundle:Categoria')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Categoria entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Categoria entity.
     *
     * @Route("/{id}/edit", name="categorias_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PublicacionesBundle:Categoria')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Categoria entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);
        $RpsStms = $this->container->get('rpsstms');
        return array(
            'entity' => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'errores' => $RpsStms->getErrorMessages($editForm),
        );
    }

    /**
    * Creates a form to edit a Categoria entity.
    *
    * @param Categoria $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Categoria $entity)
    {
        $form = $this->createForm(new CategoriaType(), $entity, array(
            'action' => $this->generateUrl('categorias_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        //$form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Categoria entity.
     *
     * @Route("/{id}", name="categorias_update")
     * @Method("PUT")
     * @Template("PublicacionesBundle:Categoria:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PublicacionesBundle:Categoria')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Categoria entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('categorias_edit', array('id' => $id)));
        }
        $RpsStms = $this->container->get('rpsstms');
        return array(
            'entity'    => $entity,
            'form'      => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'errores' => $RpsStms->getErrorMessages($editForm),
        );
    }
    /**
     * Deletes a Categoria entity.
     *
     * @Route("/{id}", name="categorias_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PublicacionesBundle:Categoria')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Categoria entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('categorias'));
    }

    /**
     * Creates a form to delete a Categoria entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('categorias_delete', array('id' => $id)))
            ->setMethod('DELETE')
            //->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
