<?php

namespace InteractiveValley\BackendBundle\Twig;

use InteractiveValley\PublicacionesBundle\Entity\Publicacion;

class StatusExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'status' => new \Twig_Filter_Method($this, 'statusFilter'),
        );
    }

    public function statusFilter($status)
    {
        switch($status){
            case Publicacion::STATUS_EN_PROCESO:
                $label = '<span class="label label-warning">En proceso</span>';
                break;
            case Publicacion::STATUS_REVISAR:
                $label = '<span class="label label-info">Revisar</span>';
                break;
            case Publicacion::STATUS_POSTEADO:
                $label = '<span class="label label-success">Publicada</span>';
                break;
        }
        return $label;
    }

    public function getName()
    {
        return "status_extension";
    }
}
