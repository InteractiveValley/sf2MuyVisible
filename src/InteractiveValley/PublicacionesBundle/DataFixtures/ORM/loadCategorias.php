<?php

namespace InteractiveValley\BackendBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use InteractiveValley\PublicacionesBundle\Entity\Categoria;

/**
 * Fixtures de la entidad Categoria.
 */
class loadCategorias extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    public function getOrder()
    {
        return 20;
    }

    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        // categoria principal
        $categoria = new Categoria();
        
        $categoria->setName('Principal');
        $categoria->setSlug('principal');
        $categoria->setPosition(1);
        $categoria->setIsActive(true);
        $manager->persist($categoria);

        $manager->flush();
    }

    
}