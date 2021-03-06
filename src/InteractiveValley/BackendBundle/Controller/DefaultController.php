<?php

namespace InteractiveValley\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class DefaultController extends Controller
{
    /**
     * @Route("/backend", name="backend_homepage")
     * @Template("BackendBundle:Default:index.html.twig")
     */
    public function backendAction(){
        /*$em = $this->getDoctrine()->getManager();
        
        $configuracion = $em->getRepository('BackendBundle:Configuraciones')
                        ->findOneBy(array('slug' => 'turn-down-for-what'));
        
        $entities = $em->getRepository('PublicacionesBundle:Publicacion')
                       ->findBy(array(),array('createdAt'=>'DESC'),5, 0);

        return array(
            'entities' => $entities,
            'configuracion'=>$configuracion,
        );*/
        return $this->redirect($this->generateUrl('publicaciones'));
    }
    
    /**
     * @Route("/backend/turn/down", name="backend_homepage_turn_down")
     * @Template("BackendBundle:Default:index.html.twig")
     */
    public function turnDownAction(){
        $em = $this->getDoctrine()->getManager();
        
        $configuracion = $em->getRepository('BackendBundle:Configuraciones')
                        ->findOneBy(array('slug' => 'turn-down-for-what'));
        $configuracion->setTexto('on');
        $em->flush();
        
        /*$entities = $em->getRepository('PublicacionesBundle:Publicacion')
                       ->findBy(array(),array('createdAt'=>'DESC'),5, 0);

        return array(
            'entities' => $entities,
            'configuracion'=>$configuracion,
        );*/
        
        return $this->redirect($this->generateUrl('configuraciones'));
    }
    
    /**
     * @Route("/backend/turn/up", name="backend_homepage_turn_up")
     * @Template("BackendBundle:Default:index.html.twig")
     */
    public function turnUpAction(){
        $em = $this->getDoctrine()->getManager();
        
        $configuracion = $em->getRepository('BackendBundle:Configuraciones')
                        ->findOneBy(array('slug' => 'turn-down-for-what'));
        $configuracion->setTexto('off');
        $em->flush();
        
        /*$entities = $em->getRepository('PublicacionesBundle:Publicacion')
                       ->findBy(array(),array('createdAt'=>'DESC'),5, 0);
        
        return array(
            'entities' => $entities,
            'configuracion'=>$configuracion,
        );*/
        
        return $this->redirect($this->generateUrl('configuraciones'));
    }
    
    /**
     * @Route("/backend/login", name="backend_login")
     * @Template()
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'BackendBundle:Security:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }
    
    /**
     * @Route("/backend/login_check", name="backend_login_check")
     */
    public function loginCheckAction()
    {
        // The security layer will intercept this request
    }

    /**
     * @Route("/backend/logout", name="backend_logout")
     */
    public function logoutAction()
    {
        // The security layer will intercept this request
        
    }
    
    /**
     * @Route("/backend/recuperar",name="backend_recuperar")
     * @Template("BackendBundle:Security:recuperar.html.twig")
     * @Method({"GET","POST"})
     */
    public function recuperarAction(Request $request) {
        $sPassword = "";
        $sUsuario = "";
        $msg = "";
        if ($request->isMethod('POST')) {
            $email = $request->get('email');
            $usuario = $this->getDoctrine()->getRepository('BackendBundle:Usuario')
                        ->findOneBy(array('email' => $email));
            if (!$usuario) {
                $this->get('session')->getFlashBag()->add(
                        'error', 'El email no esta registrado.'
                );
                return $this->redirect($this->generateUrl('recuperar'));
            } else {
                $sPassword = substr(md5(time()), 0, 7);
                $sUsuario = $usuario->getUsername();
                $encoder = $this->get('security.encoder_factory')
                        ->getEncoder($usuario);
                $passwordCodificado = $encoder->encodePassword(
                        $sPassword, $usuario->getSalt()
                );
                $usuario->setPassword($passwordCodificado);
                $this->getDoctrine()->getManager()->flush();

                $this->get('session')->getFlashBag()->add(
                        'notice', 'Se ha enviado un correo con la nueva contraseña.'
                );

                $this->enviarRecuperar($sUsuario, $sPassword, $usuario);
                return $this->redirect($this->generateUrl('login'));
            }
        }
        return array('msg' => $msg);
    }
}
