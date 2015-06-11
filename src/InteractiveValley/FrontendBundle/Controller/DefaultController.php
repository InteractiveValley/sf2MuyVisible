<?php

namespace InteractiveValley\FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use InteractiveValley\FrontendBundle\Form\ContactoType;
use InteractiveValley\FrontendBundle\Entity\Contacto;

class DefaultController extends Controller
{
    protected function getValoresSession($key,$value = array()) {
        return $this->get('session')->get($key, $value);
    }

    protected function setVAloresSession($key,$value) {
        return $this->get('session')->set($key, $value);
    }
    
    /**
     * @Route("/", name="homepage")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $contacto = new Contacto();
        $form = $this->createForm(new ContactoType(), $contacto);
        return array(
            'form'=>$form->createView()
        );
    }
    
    /**
     * @Route("/p/{slug}", name="publicacion")
     * @Template("FrontendBundle:Default:publicacion.html.twig")
     */
    public function publicacionAction(Request $request,$slug)
    {
        $noCount = $request->query->get('no-count',0);
        $em = $this->getDoctrine()->getManager();
        
        $publicacion = $em->getRepository('PublicacionesBundle:Publicacion')
                          ->findOneBy(array('slug'=>$slug));
        
        if (!$publicacion) {
            return $this->redirect($this->generateUrl('homepage'));
        }
        
        if (!$noCount) {
            $publicacionesSession = $this->getValoresSession('publicaciones');
            if (!isset($publicacionesSession[$publicacion->getSlug()])) {
                $publicacion->setContViews($publicacion->getContViews() + 1);
                $em->flush();
                $publicacionesSession[$publicacion->getSlug()] = true;
                $this->setVAloresSession('publicaciones', $publicacionesSession);
            }
        }

        return array(
            'publicacion'=>$publicacion,
        );
    }
    
    /**
     * @Route("/contacto", name="contacto")
     * @Method({"GET", "POST"})
     */
    public function contactoAction(Request $request) {
        $contacto = new Contacto();
        $form = $this->createForm(new ContactoType(), $contacto);
        $em = $this->getDoctrine()->getManager();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $datos = $form->getData();
                $message = \Swift_Message::newInstance()
                        ->setSubject('Contacto desde pagina')
                        ->setFrom($datos->getEmail())
                        ->setTo($this->container->getParameter('richpolis.emails.to_email'))
                        ->setBody($this->renderView('FrontendBundle:Default:contactoEmail.html.twig', array('datos' => $datos)), 'text/html');
                $this->get('mailer')->send($message);
                // Redirige - Esto es importante para prevenir que el usuario
                // reenvíe el formulario si actualiza la página
                $status = 'send';
                $mensaje = "Se ha enviado el mensaje";
                $contacto = new Contacto();
                $form = $this->createForm(new ContactoType(), $contacto);
            } else {
                $status = 'notsend';
                $mensaje = "El mensaje no se ha podido enviar";
            }
        } else {
            $status = 'new';
            $mensaje = "";
        }

        if ($request->isXmlHttpRequest()) {
            $vista = $this->renderView('FrontendBundle:Default:formContacto.html.twig', array(
                'form' => $form->createView(),
                'status' => $status,
                'mensaje' => $mensaje,
            ));
            return new JsonResponse(array(
                'form' => $vista,
                'status' => $status,
                'mensaje' => $mensaje,
            ));
        }

        return $this->render('FrontendBundle:Default:index.html.twig', array(
                    'form' => $form->createView(),
                    'status' => $status,
                    'mensaje' => $mensaje
        ));
    }
}
