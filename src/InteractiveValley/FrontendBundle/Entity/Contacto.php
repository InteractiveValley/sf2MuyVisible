<?php

namespace InteractiveValley\FrontendBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Contacto
{

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Ingrese su nombre")
     */
    private $name;

    /**
     * @var string
     *
     * @Assert\Email(message="Ingrese un correo valido")
     * @Assert\NotBlank(message="Ingrese su correo electronico")
     */
    private $email;

    /**
     * @var string
     *
     */
    private $subject;

    /**
     * @var string
     * 
     */
    private $phone;

    /**
     * @var string
     *
     * @Assert\Length(
     *     min=3,
     *     minMessage="El mensaje debe tener como minimo {{ limit }} caracteres."
     * )
     * @Assert\NotBlank(message="Ingresa un mensaje para ser enviado")
     */
    private $body;


    /**
     * Set name
     *
     * @param string $name
     * @return Contacto
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Contacto
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return Contacto
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Contacto
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Contacto
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }
}
