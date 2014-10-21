<?php

namespace Pumukit\SchemaBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

use Pumukit\SchemaBundle\Document\Tag;
use Pumukit\SchemaBundle\Document\MultimediaObject;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Pumukit\SchemaBundle\Document\Tag
 * 
 * @MongoDB\EmbeddedDocument
 */
class Tag
{
  /**
   * @var integer $id
   *
   * @MongoDB\Id
   */
  private $id;

  /**
   * @var collection $multimedia_objects
   *
   * @MongoDB\EmbedMany(targetDocument="MultimediaObject")
   */
  private $multimedia_objects;

  /**
   * @var string $title
   * //Translatable
   *
   * @MongoDB\Raw
   */
  private $title = array('en'=>'');

  /**
   * @var string $description
   * //Translatable
   *
   * @MongoDB\Raw
   */
  private $description = array('en'=>'');

  /**
   * @var string $slug
   *
   * @MongoDB\String
   */
  private $slug;

  /**
   * @var string $cod
   *
   * @MongoDB\String
   */
  private $cod = 0;

  /**
   * @var boolean $metatag
   *
   * @MongoDB\Boolean
   */
  private $metatag = false;

  /**
   * Used locale to override Translation listener`s locale
   * this is not a mapped field of entity metadata, just a simple property
   * @var locale $locale
   */
  private $locale = 'en';

  /**
   * @var int $left
   * //TreeLeft
   * @MongoDB\Int
   */
  private $left;

  /**
   * @var int $right
   * //TreeRight
   * @MongoDB\Int
   */
  private $right;

  /**
   * //TreeParent
   * @MongoDB\ReferenceOne(targetDocument="Tag")
   * @MongoDB\Index 
   * //@MongoDB\ManyToOne(targetDocument="Tag", inversedBy="children")
   * //@MongoDB\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="SET NULL")
   */
  private $parent;

  /**
   * @var int $root
   * //TreeRoot
   * @MongoDB\ReferenceOne(targetDocument="Tag")
   * @MongoDB\Int
   */
  private $root;

  /**
   * @var int $level
   * //TreeLevel
   * @MongoDB\Int
   */
  private $level;

  /**
   * @MongoDB\ReferenceMany(targetDocument="Tag")
   * @MongoDB\Index
   * //@MongoDB\OneToMany(targetDocument="Tag", mappedBy="parent")
   * //MongoDB\OrderBy({"left" = "ASC"})
   */
  private $children;

  /**
   * @var date $created
   *
   * @MongoDB\Date
   */
  private $created;

  /**
   * @var date $updated
   *
   * @MongoDB\Date
   */
  private $updated;

  public function __construct($title = null)
  {
    $this->children = new ArrayCollection();
    if ($title != null) {
      $this->setTitle($title);
    }
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
   */
  public function setTitle($title, $locale = null)
  {
    if ($locale == null) {
      $locale = $this->locale;
    }
    $this->title[$locale] = $title;
  }

  /**
   * Get title
   *
   * @return string
   */
  public function getTitle($locale = null)
  {
    if ($locale == null) {
      $locale = $this->locale;
    }
    if (!isset($this->title[$locale])){
      return null;
    }
    return $this->title[$locale];
  }

  /**
   * Set description
   * 
   * @param string $description
   */
  public function setDescription($description, $locale = null)
  {
    if ($locale == null) {
      $locale = $this->locale;
    }
    $this->description[$locale] = $description;
  }

  /**
   * Get description
   * 
   * @return string
   */
  public function getDescription($locale = null)
  {
    if ($locale == null) {
      $locale = $this->locale;
    }
    if (!isset($this->description[$locale])){
      return null;
    }
    return $this->description[$locale];
  }

  /**
   * Set slug
   *
   * @param string $slug
   * @return Tag
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
   * Set metatag
   *
   * @param boolean $metatag
   */
  public function setMetatag($metatag)
  {
    $this->metatag = $metatag;
  }

  /**
   * Get metatag
   *
   * @return boolean
   */
  public function getMetatag()
  {
    return $this->metatag;
  }


  /**
   * Set parent
   * 
   * @param Tag $parent
   */
  public function setParent(Tag $parent)
  {
    $this->parent = $parent;
  }

  /**
   * Get parent
   * 
   * @return Tag
   */
  public function getParent()
  {
    return $this->parent;
  }

  /**
   * Set root
   *
   * @param int $root
   * @return Tag
   */
  public function setRoot($root)
  {
    $this->root = $root;

    return $this;
  }

  /**
   * Get root
   * 
   * @return int
   */
  public function getRoot()
  {
    return $this->root;
  }

  /**
   * Set level
   *
   * @param int $level
   * @return Tag
   */
  public function setLevel($level)
  {
    $this->level = $level;

    return $this;
  }

  /**
   * Get level
   *
   * @return int
   */
  public function getLevel()
  {
    return $this->level;
  }

  /**
   * Get children
   *
   * @return Tag
   */
  public function getChildren()
  {
    return $this->children;
  }
 
  /**
   * Set created
   *
   * @param \Date $created
   * @return Tag
   */
  public function setCreated($created)
  {
    $this->created = $created;

    return $this;
  }

  /**
   * Get created
   *
   * @return Date
   *
   */
  public function getCreated()
  {
    return $this->created;
  }

  /**
   * Set updated
   *
   * @param \Date $updated
   * @return Tag
   */
  public function setUpdated($updated)
  {
    $this->updated = $updated;

    return $this;
  }

  /**
   * Get updated
   *
   * @return Date
   */
  public function getUpdated()
  {
    return $this->updated;
  }

  /**
   * Set translatable locale
   *
   * @param locale $locale
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
   * to string
   *
   * @return string
   */
  public function __toString()
  {
    return $this->getTitle();
  }

  /**
   * Set left
   *
   * @param integer $left
   * @return Tag
   */
  public function setLeft($left)
  {
    $this->left = $left;

    return $this;
  }

  /**
   * Get left
   *
   * @return int
   */
  public function getLeft()
  {
    return $this->left;
  }

  /**
   * Set right
   *
   * @param int $right
   * @return Tag
   */
  public function setRight($right)
  {
    $this->right = $right;

    return $this;
  }

  /**
   * Get right
   *
   * @return int
   */
  public function getRight()
  {
    return $this->right;
  }

  /**
   * Add multimedia_objects
   *
   * @param MultimediaObject $multimediaObjects
   * @return Tag
   */
  public function addMultimediaObject(MultimediaObject $multimediaObjects)
  {
    $this->multimedia_objects[] = $multimediaObjects;

    return $this;
  }

  /**
   * Remove multimedia_objects
   *
   * @param MultimediaObject $multimediaObjects
   */
  public function removeMultimediaObject(MultimediaObject $multimediaObjects)
  {
    $this->multimedia_objects->removeElement($multimediaObjects);
  }

  /**
   * Get multimedia_objects
   *
   * @return Collection
   */
  public function getMultimediaObjects()
  {
    return $this->multimedia_objects;
  }

  /**
   * Add children
   *
   * @param Tag $children
   * @return Tag
   */
  public function addChildren(Tag $children)
  {
    $this->children[] = $children;

    return $this;
  }

  /**
   * Remove children
   *
   * @param Tag $children
   */
  public function removeChildren(Tag $children)
  {
    $this->children->removeElement($children);
  }
}