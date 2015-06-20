<?php

namespace InteractiveValley\PublicacionesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Publicacion
 *
 * @ORM\Table(name="publications")
 * @ORM\Entity(repositoryClass="InteractiveValley\PublicacionesBundle\Entity\PublicacionRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Publicacion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank(message="Ingresa el titulo de la publicacion")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank(message="Ingresa la descripcion de la publicacion")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="resume", type="text")
     * @Assert\NotBlank(message="Escribe el breve resumen de la publicacion")
     */
    private $resume;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var integer
     *
     * @ORM\Column(name="contComments", type="integer")
     */
    private $contComments = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="contLikes", type="integer")
     */
    private $contLikes =  0;

    /**
     * @var integer
     *
     * @ORM\Column(name="contTwits", type="integer")
     */
    private $contTwits = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="contViews", type="integer")
     */
    private $contViews = 0;
    
    /**
     * @var string
     *
     * @ORM\Column(name="referencia", type="string", length=255,nullable=true)
     */
    private $referencia;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=true)
     */
    private $isActive;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var integer
     *
     * @var InteractiveValley\PublicacionesBundle\Entity\Categoria
     * @todo Usuario validador del envio
     *
     * @ORM\ManyToOne(targetEntity="Categoria", inversedBy="publications")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie_id", referencedColumnName="id")
     * })
     */
    private $categorie;

    /**
     * @var InteractiveValley\BackendBundle\Entity\Usuario
     * @todo Usuario validador del envio
     *
     * @ORM\ManyToOne(targetEntity="InteractiveValley\BackendBundle\Entity\Usuario", inversedBy="publications")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     * })
     */
    private $author;
    
    const STATUS_EN_PROCESO  =   1;
    const STATUS_REVISAR     =   2;
    const STATUS_POSTEADO    =   3;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status = 0;
    
    public function __construct() {
        $this->status = self::STATUS_EN_PROCESO;
        $this->isActive = false;
    }
    
    /*
     * Timestable
     */
    
    /**
     ** @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        if(!$this->getCreatedAt())
        {
            $this->createdAt = new \DateTime();
        }
        if(!$this->getUpdatedAt())
        {
            $this->updatedAt = new \DateTime();
        }
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new \DateTime();
    }
    
    /*** uploads ***/
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->image)) {
            // store the old name to delete after the update
            $this->temp = $this->image;
            $this->image = null;
        } else {
            $this->image = 'initial';
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }
    
    /**
    * @ORM\PrePersist
    * @ORM\PreUpdate
    */
    public function preUpload()
    {
      if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->image = $filename.'.'.$this->getFile()->guessExtension();
        }
    }

    /**
    * @ORM\PostPersist
    * @ORM\PostUpdate
    */
    public function upload()
    {
      if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->image);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }

    /**
    * @ORM\PostRemove
    */
    public function removeUpload()
    {
      if ($file = $this->getAbsolutePath()) {
        if(file_exists($file)){
            unlink($file);
        }
      }
    }
    
    protected function getUploadDir()
    {
        return '/uploads/publicaciones';
    }

    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../web'.$this->getUploadDir();
    }
    
    public function getWebPath()
    {
        return null === $this->image ? null : $this->getUploadDir().'/'.$this->image;
    }
    
    public function getAbsolutePath()
    {
        return null === $this->image ? null : $this->getUploadRootDir().'/'.$this->image;
    }
    
    // custom functions
    
    public function getIsEnProceso(){
        return ($this->getStatus()==self::STATUS_EN_PROCESO);
    }
    
    public function getIsPosteada(){
        return ($this->getStatus()==self::STATUS_POSTEADO) && ($this->isActive);
    }
    
    public function getIsRevisar(){
        return ($this->getStatus()==self::STATUS_REVISAR) && ($this->isActive==false);
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Publicacion
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Publicacion
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Publicacion
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set resume
     *
     * @param string $resume
     * @return Publicacion
     */
    public function setResume($resume)
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * Get resume
     *
     * @return string 
     */
    public function getResume()
    {
        return $this->resume;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Publicacion
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set contComments
     *
     * @param integer $contComments
     * @return Publicacion
     */
    public function setContComments($contComments)
    {
        $this->contComments = $contComments;

        return $this;
    }

    /**
     * Get contComments
     *
     * @return integer 
     */
    public function getContComments()
    {
        return $this->contComments;
    }

    /**
     * Set contLikes
     *
     * @param integer $contLikes
     * @return Publicacion
     */
    public function setContLikes($contLikes)
    {
        $this->contLikes = $contLikes;

        return $this;
    }

    /**
     * Get contLikes
     *
     * @return integer 
     */
    public function getContLikes()
    {
        return $this->contLikes;
    }

    /**
     * Set contTwits
     *
     * @param integer $contTwits
     * @return Publicacion
     */
    public function setContTwits($contTwits)
    {
        $this->contTwits = $contTwits;

        return $this;
    }

    /**
     * Get contTwits
     *
     * @return integer 
     */
    public function getContTwits()
    {
        return $this->contTwits;
    }

    /**
     * Set contViews
     *
     * @param integer $contViews
     * @return Publicacion
     */
    public function setContViews($contViews)
    {
        $this->contViews = $contViews;

        return $this;
    }

    /**
     * Get contViews
     *
     * @return integer 
     */
    public function getContViews()
    {
        return $this->contViews;
    }

    /**
     * Set position
     *
     * @param integer $position
     * @return Publicacion
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Publicacion
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Publicacion
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Publicacion
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set categoria
     *
     * @param integer $categoria
     * @return Publicacion
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return integer 
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set usuario
     *
     * @param integer $usuario
     * @return Publicacion
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return integer 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set categorie
     *
     * @param \InteractiveValley\PublicacionesBundle\Entity\Categoria $categorie
     * @return Publicacion
     */
    public function setCategorie(\InteractiveValley\PublicacionesBundle\Entity\Categoria $categorie = null)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \InteractiveValley\PublicacionesBundle\Entity\Categoria 
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set author
     *
     * @param \InteractiveValley\BackendBundle\Entity\Usuario $author
     * @return Publicacion
     */
    public function setAuthor(\InteractiveValley\BackendBundle\Entity\Usuario $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return \InteractiveValley\BackendBundle\Entity\Usuario 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Publicacion
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set referencia
     *
     * @param string $referencia
     * @return Publicacion
     */
    public function setReferencia($referencia)
    {
        $this->referencia = $referencia;

        return $this;
    }

    /**
     * Get referencia
     *
     * @return string 
     */
    public function getReferencia()
    {
        return $this->referencia;
    }
}
