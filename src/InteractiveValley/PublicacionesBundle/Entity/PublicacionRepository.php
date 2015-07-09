<?php

namespace InteractiveValley\PublicacionesBundle\Entity;

use Doctrine\ORM\EntityRepository;
use InteractiveValley\PublicacionesBundle\Entity\Publicacion;

/**
 * PublicacionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PublicacionRepository extends EntityRepository
{
    public function getMaxPosicion(){
        $em=$this->getEntityManager();
       
        $query=$em->createQuery('
            SELECT MAX(c.position) as value 
            FROM PublicacionesBundle:Publicacion c 
            ORDER BY c.position ASC
        ');
        
        $max=$query->getResult();
        return $max[0]['value'];
    }
    
    public function queryFindRecientes(){
        $query = $this->getEntityManager()->createQueryBuilder();
        $query->select('p')
                ->from('InteractiveValley\PublicacionesBundle\Entity\Publicacion', 'p')
                ->where('p.status=:posteada')
                ->setParameter('posteada', Publicacion::STATUS_POSTEADO)
                ->orderBy('p.createdAt', 'DESC');
        return $query->getQuery();
    }
    
    public function queryFindPublicacionesMasVistas(){
        $query = $this->getEntityManager()->createQueryBuilder();
        $query->select('p')
                ->from('InteractiveValley\PublicacionesBundle\Entity\Publicacion', 'p')
                ->where('p.status=:posteada')
                ->setParameter('posteada', Publicacion::STATUS_POSTEADO)
                ->orderBy('p.contViews', 'DESC');
        return $query->getQuery();
    }
    
    public function findPublicacionesMasVistas($max = 10){
        $query = $this->queryFindPublicacionesMasVistas();
        return $query->setMaxResults($max)->getResult();
    }
    
    public function queryFindPublicacionesRelacionadas($excepto = 0){
        $query = $this->getEntityManager()->createQueryBuilder();
        $query->select('p')
                ->from('InteractiveValley\PublicacionesBundle\Entity\Publicacion', 'p')
                ->where('p.id<>:exceptoId')
                ->andWhere('p.status=:posteada')
                ->setParameters(array('exceptoId'=>$excepto,'posteada'=>Publicacion::STATUS_POSTEADO))
                ->orderBy('p.contViews', 'DESC');
        return $query->getQuery();
    }
    
    public function findPublicacionesRelacionadas($excepto = 0, $max=3){
        $query = $this->queryFindPublicacionesRelacionadas($excepto);
        return $query->setMaxResults($max)->getResult();
    }
    
    public function findTitleSluggable($slug, $excepto = 0){
        $query= $this->getEntityManager()->createQueryBuilder();
        if($excepto > 0){
            $query->select('p')
                ->from('InteractiveValley\PublicacionesBundle\Entity\Publicacion', 'p')
                ->where('p.id<>:publicacion')
                ->setParameter('publicacion',$excepto)
                ->andWhere('p.slug LIKE :slug')
                ->setParameter('slug',$slug."%")
                ->orderBy('p.title', 'DESC'); 
        }else{
            $query->select('p')
                ->from('InteractiveValley\PublicacionesBundle\Entity\Publicacion', 'p')
                ->andWhere('p.slug LIKE :slug')
                ->setParameter('slug',$slug."%")
                ->orderBy('p.title', 'DESC'); 
        }
        return $query->getQuery()->getResult();
    }
}
