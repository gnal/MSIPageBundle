<?php

namespace Msi\Bundle\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="page")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Page
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column()
     * @Assert\NotBlank()
     */
    protected $template;

    /**
     * @ORM\Column()
     * @Assert\NotBlank()
     */
    protected $layout;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $css;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $js;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $enabled;

    /**
     * @ORM\OneToMany(targetEntity="Block", mappedBy="page", cascade={"remove"})
     */
    protected $blocks;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", name="updated_at")
     */
    protected $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="PageTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->enabled = false;
        $this->blocks = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

    public function addBlock($block)
    {
        $this->blocks[] = $block;

        return $this;
    }

    public function getBlocks()
    {
        return $this->blocks;
    }

    public function getCss()
    {
        return $this->css;
    }

    public function setCss($css)
    {
        $this->css = $css;

        return $this;
    }

    public function getJs()
    {
        return $this->js;
    }

    public function setJs($js)
    {
        $this->js = $js;

        return $this;
    }

    public function addTranslation($translation)
    {
        $this->translations[] = $translation;

        $translation->setObject($this);

        return $this;
    }

    public function getTranslations()
    {
        $fixedTranslations = new ArrayCollection();
        foreach ($this->translations as $t) {
            $fixedTranslations[$t->getLocale()] = $t;
        }

        return $fixedTranslations;
    }

    public function getTranslation()
    {
        return $this->translations[0];
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    public function getLayout()
    {
        return $this->layout;
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;

        return $this;
    }

    public function getEnabled()
    {
        return $this->enabled;
    }

    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->getTranslation()->getTitle();
    }

    static public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text)) {

            return 'n-a';
        }

        return $text;
    }
}
