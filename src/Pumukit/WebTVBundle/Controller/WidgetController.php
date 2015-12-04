<?php

namespace Pumukit\WebTVBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Pumukit\SchemaBundle\Document\MultimediaObject;
use Pumukit\SchemaBundle\Document\Series;

class WidgetController extends Controller
{
    /**
     * @Template()
     */
    public function menuAction()
    {
        $channels = $this->get('doctrine_mongodb')->getRepository('PumukitLiveBundle:Live')->findAll();
        $selected = $this->container->get('request_stack')->getMasterRequest()->get('_route');
        $menuStats = true;
        if($this->container->hasParameter('menu_stats')) {
          $menuStats = $this->container->getParameter('menu_stats');
        }

        $homeTitle = $this->container->hasParameter('menu.home_title') ? $this->container->getParameter('menu.home_title') : 'Home';
        $announcesTitle = $this->container->hasParameter('menu.announces_title') ? $this->container->getParameter('menu.announces_title') : 'Latest uploads';
        $searchTitle = $this->container->hasParameter('menu.search_title') ? $this->container->getParameter('menu.search_title') : 'Search';
        $mediatecaTitle = $this->container->hasParameter('menu.mediateca_title') ? $this->container->getParameter('menu.mediateca_title') : 'Full Catalogue';
        $categoriesTitle = $this->container->hasParameter('menu.categories_title') ? $this->container->getParameter('menu.categories_title') : 'Videos by category';

        return array('live_channels' => $channels, 'menu_selected' => $selected, 'menu_stats' => $menuStats,
        'home_title' => $homeTitle,
        'announces_title' => $announcesTitle,
        'search_title' => $searchTitle,
        'mediateca_title' => $mediatecaTitle,
        'categories_title' => $categoriesTitle,
        'menu_stats' => $menuStats);
    }

    /**
     * @Template()
     */
    public function breadcrumbsAction()
    {
        $breadcrumbs = $this->get('pumukit_web_tv.breadcrumbs');

        return array('breadcrumbs' => $breadcrumbs->getBreadcrumbs());
    }

    /**
     * @Template()
     */
    public function statsAction()
    {
        $mmRepo = $this->get('doctrine_mongodb')->getRepository('PumukitSchemaBundle:MultimediaObject');
        $seriesRepo = $this->get('doctrine_mongodb')->getRepository('PumukitSchemaBundle:series');

        $counts = array('series' => $seriesRepo->countPublic(),
                        'mms' => $mmRepo->count(),
                        'hours' => bcdiv($mmRepo->countDuration(), 3600, 2), );

        return array('counts' => $counts);
    }

    /**
     * @Template()
     */
    public function contactAction()
    {
        return array();
    }

    /**
     * @Template("PumukitWebTVBundle:Widget:upcomingliveevents.html.twig")
     */
    public function upcomingLiveEventsAction()
    {
        $dm = $this->get('doctrine_mongodb.odm.document_manager');
        $eventRepo = $dm->getRepository('PumukitLiveBundle:Event');
        $events = $eventRepo->findFutureAndNotFinished(5);

        return array('events' => $events);
    }

    /**
     * @Template()
     */
    public function languageselectAction()
    {
        $array_locales = $this->container->getParameter('pumukit2.locales');
        if (count($array_locales) <= 1) {
            return new Response('');
        }

        return array('languages' => $array_locales);
    }
}
