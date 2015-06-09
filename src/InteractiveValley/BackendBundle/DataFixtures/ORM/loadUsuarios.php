<?php

namespace InteractiveValley\BackendBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use InteractiveValley\BackendBundle\Entity\Usuario;

/**
 * Fixtures de la entidad Usuario.
 */
class loadUsuarios extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    public function getOrder()
    {
        return 10;
    }

    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        // Superadmin
        $admin = new Usuario();
        
        $admin->setSalt(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));
        $passwordEnClaro = 'D3m3s1s1';
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($admin);
        $passwordCodificado = $encoder->encodePassword($passwordEnClaro, $admin->getSalt());
        $admin->setPassword($passwordCodificado);
        $admin->setName("Interactive Valley");
        $admin->setEmail('richpolis@gmail.com');
        $admin->setType(Usuario::GROUP_SUPER_ADMIN);
        $manager->persist($admin);
        
        
        

        $manager->flush();
    }

    
}