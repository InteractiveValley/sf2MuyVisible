<?php

namespace InteractiveValley\PublicacionesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use InteractiveValley\PublicacionesBundle\Entity\Publicacion;
use InteractiveValley\PublicacionesBundle\Form\PublicacionType;

/**
 * Publicacion controller.
 *
 * @Route("/backend/publicaciones")
 */
class PublicacionController extends Controller
{

    /**
     * Lists all Publicacion entities.
     *
     * @Route("/", name="publicaciones")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PublicacionesBundle:Publicacion')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Publicacion entity.
     *
     * @Route("/", name="publicaciones_create")
     * @Method("POST")
     * @Template("PublicacionesBundle:Publicacion:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Publicacion();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('publicaciones_show', array('id' => $entity->getId())));
        }
        $RpsStms = $this->container->get('rpsstms');
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'errores' => $RpsStms->getErrorMessages($form),
        );
    }

    /**
     * Creates a form to create a Publicacion entity.
     *
     * @param Publicacion $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Publicacion $entity)
    {
        $form = $this->createForm(new PublicacionType(), $entity, array(
            'action' => $this->generateUrl('publicaciones_create'),
            'method' => 'POST',
            'em'=>$this->getDoctrine()->getManager(),
        ));

        //$form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Publicacion entity.
     *
     * @Route("/new", name="publicaciones_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Publicacion();
        $max = $this->getDoctrine()->getRepository('PublicacionesBundle:Publicacion')
                                   ->getMaxPosicion();
        if (!is_null($max)) {
            $entity->setPosition($max + 1);
        } else {
            $entity->setPosition(1);
        }
        $entity->setAuthor($this->getUser());
        $form   = $this->createCreateForm($entity);
        $RpsStms = $this->container->get('rpsstms');
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'errores' => $RpsStms->getErrorMessages($form),
        );
    }

    /**
     * Finds and displays a Publicacion entity.
     *
     * @Route("/{id}", name="publicaciones_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PublicacionesBundle:Publicacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Publicacion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Publicacion entity.
     *
     * @Route("/{id}/edit", name="publicaciones_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PublicacionesBundle:Publicacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Publicacion entity.');
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
    * Creates a form to edit a Publicacion entity.
    *
    * @param Publicacion $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Publicacion $entity)
    {
        $form = $this->createForm(new PublicacionType(), $entity, array(
            'action' => $this->generateUrl('publicaciones_update', array('id' => $entity->getId())),
            'method' => 'PUT',
            'em'=>$this->getDoctrine()->getManager(),
        ));

        //$form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Publicacion entity.
     *
     * @Route("/{id}", name="publicaciones_update")
     * @Method("PUT")
     * @Template("PublicacionesBundle:Publicacion:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PublicacionesBundle:Publicacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Publicacion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $this->setTitleSluggable($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('publicaciones_edit', array('id' => $id)));
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
     * Deletes a Publicacion entity.
     *
     * @Route("/{id}", name="publicaciones_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PublicacionesBundle:Publicacion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Publicacion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('publicaciones'));
    }

    /**
     * Creates a form to delete a Publicacion entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('publicaciones_delete', array('id' => $id)))
            ->setMethod('DELETE')
            //->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    private function setTitleSluggable(Publicacion $entity, $isNew = false){
        $em = $this->getDoctrine()->getManager();
        $RpsStms = $this->container->get('rpsstms');
        $entity->setSlug($RpsStms->slugify($entity->getTitle()));
        $slug = $entity->getSlug();
        $id = 0;
        if(!$isNew){
            $id = $entity->getId();
        }
        $resultados = $em->getRepository('PublicacionesBundle:Publicacion')
                         ->findTitleSluggable($slug,$id);
        if(count($resultados)>0){
            $cont=0;
            $encontrado = false;
            do{
                //buscamos el slug correcto
                $slugBuscar = $slug .($cont>0?'-'.$cont:'');
                foreach($resultados as $resultado){
                    if($resultado->getSlug() == $slugBuscar){
                        $encontrado=true;
                        break;
                    }else{
                        $encontrado = false;
                    }
                }
                //entonces empecemos a buscar otro slug
                $cont++;
            }while($encontrado);
            $slug = $slugBuscar;    
        }   
        $entity->setSlug($slug);
        return true;
    }
}
