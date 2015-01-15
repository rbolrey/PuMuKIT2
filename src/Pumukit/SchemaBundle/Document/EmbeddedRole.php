<?php

namespace Pumukit\SchemaBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Pumukit\SchemaBundle\Document\EmbeddedRole
 *
 * @MongoDB\EmbeddedDocument()
 */
class EmbeddedRole
{
    /**
     * @var string $id
     *
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @var string $cod
     *
     * @MongoDB\String
     */
    private $cod = '0';

    /**
     * See European Broadcasting Union Role Codes
     * @var string $xml
     *
     * @MongoDB\String
     */
    private $xml;
    
    /**
     * @var boolean $display
     *
     * @MongoDB\Boolean
     */
    private $display = true;
    
    /**
     * @var string $name
     *
     * @MongoDB\Raw
     */
    private $name = array('en' => '');
    
    /**
     * @var string $text
     *
     * @MongoDB\Raw
     */
    private $text = array('en' => '');
    
    /**
     * @var locale $locale
     */
    private $locale = 'en';
    
    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set cod
     *
     * @param string $cod
     */
    public function setCod($cod)
    {
        $this->cod = $cod;
    }
    
    /**
     * Get cod
     *
     * @return string
     */
    public function getCod()
    {
        return $this->cod;
    }

    /**
     * Set xml
     *
     * @param string $xml
     */
    public function setXml($xml)
    {
        $this->xml = $xml;
    }
    
    /**
     * Get xml
     *
     * @return string
     */
    public function getXml()
    {
        return $this->xml;
    }
    
    /**
     * Set display
     *
     * @param boolean $display
     */
    public function setDisplay($display)
    {
        $this->display = $display;
    }
    
    /**
     * Get display
     *
     * @return boolean
     */
    public function getDisplay()
    {
        return $this->display;
    }
    
    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name, $locale = null)
    {
        if ($locale == null) {
            $locale = $this->locale;
        }
        $this->name[$locale] = $name;
    }
    
    /**
     * Get name
     *
     * @return string
     */
    public function getName($locale = null)
    {
        if ($locale == null) {
            $locale = $this->locale;
        }
        if (!isset($this->name[$locale])) {
            return;
        }
        
        return $this->name[$locale];
    }

    /**
     * Set I18n name
     *
     * @param array $name
     */
    public function setI18nName(array $name)
    {
        $this->name = $name;
    }

    /**
     * Get i18n name
     *
     * @return array
     */
    public function getI18nName()
    {
        return $this->name;
    }

    /**
     * Set text
     *
     * @param string $text
     */
    public function setText($text, $locale = null)
    {
        if ($locale == null) {
            $locale = $this->locale;
        }
        $this->text[$locale] = $text;
    }
    
    /**
     * Get text
     *
     * @return string
     */
    public function getText($locale = null)
    {
        if ($locale == null) {
            $locale = $this->locale;
        }
        if (!isset($this->text[$locale])) {
            return;
        }
        
        return $this->text[$locale];
    }
    
    /**
     * Set I18n text
     *
     * @param array $text
     */
    public function setI18nText(array $text)
    {
        $this->text = $text;
    }
    
    /**
     * Get i18n text
     *
     * @return array
     */
    public function getI18nText()
    {
        return $this->text;
    }

    /**
     * Set locale
     *
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * Get locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Constructor
     */
    public function __construct(Role $role)
    {
        if (null !== $role){
            $this->id = $role->getId();
            $this->cod = $role->getCod();
            $this->xml = $role->getXml();
            $this->display = $role->getDisplay();
            $this->setI18nName($role->getI18nName());
            $this->setI18nText($role->getI18nText());
        }
    }

    /**
     * Create embedded role
     *
     * @param ArrayCollection $embeddedRoles
     * @param EmbeddedRole|Role $role
     *
     * @return EmbeddedRole
     */
    public static function createEmbeddedRole($embeddedRoles, $role)
    {
        if ($role instanceof self){
            return $role;
        }elseif ($containedEmbedRole = self::getEmbeddedRole($embeddedRoles, $role)) {
            return $containedEmbedRole;
        }elseif ($role instanceof Role){
            $embeddedRole = new self($role);
            
            return $embeddedRole;
        }
        
        throw new \InvalidArgumentException('Only Role or EmbeddedRole are allowed.');
    }

    /**
     * Get embedded role
     *
     * @param ArrayCollection $embeddedRoles
     * @param Role|EmbeddedRole $role
     * @return EmbeddedRole|boolean EmbeddedRole if found, FALSE otherwise.
     */
    public static function getEmbeddedRole($embeddedRoles, $role)
    {
        foreach ($embeddedRoles as $embeddedRole) {
            if (0 === strcmp($role->getCod(), $embeddedRole->getCod())) {
                return $embeddedRole;
            }
        }
        
        return false;
    }
}