<?php

namespace Pumukit\AdminBundle\Form\Type\Other;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class DirectqualitiesType extends AbstractType
{
		      
  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
    $resolver->setDefaults(array(
        'compound' => false,
    ));
  }

  public function getName()
  {
    return 'directqualities';
  }
}