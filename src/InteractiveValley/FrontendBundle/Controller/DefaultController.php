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
use InteractiveValley\BackendBundle\Utils\Richsys as RpsStms;

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
                $configuracion = $em->getRepository('BackendBundle:Configuraciones')
                                    ->findOneBy(array('slug' => 'email-contacto'));
                $datos = $form->getData();
                $message = \Swift_Message::newInstance()
                        ->setSubject('Contacto desde pagina')
                        ->setFrom($datos->getEmail())
                        ->setTo($configuracion->getTexto())
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
    
    /**
     * @Route("/{slug}", name="publicacion")
     * @Template("FrontendBundle:Default:publicacion.html.twig")
     */
    public function publicacionAction(Request $request,$slug)
    {
        $noCount = $request->query->get('no-count',0);
        $em = $this->getDoctrine()->getManager();
        
        $configuracion = $em->getRepository('BackendBundle:Configuraciones')
                        ->findOneBy(array('slug' => 'turn-down-for-what'));
        
        if($configuracion->getTexto()=='on'){
            return $this->redirect($this->generateUrl('homepage'));
        }
        
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
        $url= $this->generateUrl('publicacion', array('slug'=>$publicacion->getSlug()), true);
        //var_dump($url); die;
        //$url = "http://www.fastcodesign.com/3047272/attention-shoppers-brace-yourselves-for-beacons";
        try{
            $shareFacebook = RpsStms::getCountShareFacebook($url);
            $shareTwitter = RpsStms::getCountShareTwitter($url);
        }catch(\RuntimeException $e){
            $shareFacebook = 0;
            $shareTwitter = 0;
        }
        //var_dump(array('shareFacebook' => $shareFacebook,'shareTwitter' => $shareTwitter)); die;
        return array(
            'publicacion'=>$publicacion,
            'shareFacebook' => $shareFacebook,
            'shareTwitter' => $shareTwitter
        );
    }
    
    
}
