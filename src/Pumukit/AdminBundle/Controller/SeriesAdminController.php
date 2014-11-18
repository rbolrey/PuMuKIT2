<?php

namespace Pumukit\AdminBundle\Controller;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class SeriesAdminController extends AdminController
{
  /**
   * Overwrite to search criteria with date
   */
  public function indexAction(Request $request)
  {
      $config = $this->getConfiguration();

      $criteria = $this->getCriteria($config);
      $resources = $this->getResources($request, $config, $criteria);

      $pluralName = $config->getPluralResourceName();

      $view = $this
      ->view()
      ->setTemplate($config->getTemplate('index.html'))
      ->setTemplateVar($pluralName)
      ->setData($resources)
      ;

      return $this->handleView($view);
  }

  /**
   * Create new resource
   */
  public function createAction(Request $request)
  {
      $config = $this->getConfiguration();
      $pluralName = $config->getPluralResourceName();

      $series = $this->get('pumukitschema.factory');
      $series->createSeries($this);

      $this->setFlash('success', 'create');

      $criteria = $this->_getCriteria($config);
      $resources = $this->_getResources($request, $config, $criteria);

      $pluralName = $config->getPluralResourceName();

      $view = $this
      ->view()
      ->setTemplate($config->getTemplate('list.html'))
      ->setTemplateVar($pluralName)
      ->setData($resources)
      ;

      return $this->handleView($view);
  }

  // TODO
  /**
   * Display the form for editing or update the resource.
   */
  public function updateAction(Request $request)
  {
      $config = $this->getConfiguration();

      $resource = $this->findOr404();
      $form = $this->getForm($resource);
      
      if (($request->isMethod('PUT') || $request->isMethod('POST')) && $form->bind($request)->isValid()) {
          $event = $this->update($resource);
          if (!$event->isStopped()) {
              $this->setFlash('success', 'update');

	      $criteria = $this->_getCriteria($config);
	      $resources = $this->_getResources($request, $config, $criteria);	      

	      $pluralName = $config->getPluralResourceName();
	      
	      $view = $this
		->view()
		->setTemplate($config->getTemplate('list.html'))
		->setTemplateVar($pluralName)
		->setData($resources)
		;
	      
	      return $this->handleView($view);
          }

          $this->setFlash($event->getMessageType(), $event->getMessage(), $event->getMessageParams());
      }

      if ($config->isApiRequest()) {
          return $this->handleView($this->view($form));
      }

      $view = $this
      ->view()
      ->setTemplate($config->getTemplate('update.html'))
      ->setData(array(
              $config->getResourceName() => $resource,
              'form'                     => $form->createView(),
              ))
      ;

      return $this->handleView($view);
  }

  /**
   * Gets the criteria values
   */
  public function getCriteria($config)
  {
      $criteria = $config->getCriteria();

      if (array_key_exists('reset', $criteria)) {
          $this->get('session')->remove('admin/'.$config->getResourceName().'/criteria');
      } elseif ($criteria) {
          $this->get('session')->set('admin/'.$config->getResourceName().'/criteria', $criteria);
      }
      $criteria = $this->get('session')->get('admin/'.$config->getResourceName().'/criteria', array());

    //TODO: do upstream
    $new_criteria = array();
      foreach ($criteria as $property => $value) {
          //preg_match('/^\/.*?\/[imxlsu]*$/i', $e)
      if (('' !== $value) && ('date' !== $property)) {
          $new_criteria[$property] = new \MongoRegex('/'.$value.'/i');
      } elseif (('' !== $value) && ('date' == $property)) {
          $date_from = new \DateTime($value['from']);
          $date_to = new \DateTime($value['to']);
          $new_criteria[$property] = ['$gte' => $date_from, '$lt' => $date_to];
      }
      }

      return $new_criteria;
  }

}
