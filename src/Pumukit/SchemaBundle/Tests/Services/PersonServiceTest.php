<?php

namespace Pumukit\SchemaBundle\Tests\Services;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Pumukit\SchemaBundle\Document\Person;
use Pumukit\SchemaBundle\Document\Role;
use Pumukit\SchemaBundle\Document\MultimediaObject;
use Pumukit\SchemaBundle\Document\Series;

class PersonServiceTest extends WebTestCase
{
    private $dm;
    private $repo;
    private $personService;

    public function __construct()
    {
        $options = array('environment' => 'test');
        $kernel = static::createKernel($options);
        $kernel->boot();

        $this->dm = $kernel->getContainer()
          ->get('doctrine_mongodb')->getManager();
        $this->repo = $this->dm
          ->getRepository('PumukitSchemaBundle:Person');
        $this->personService = $kernel->getContainer()
          ->get('pumukitschema.person');
    }

    public function setUp()
    {
        $this->dm->getDocumentCollection('PumukitSchemaBundle:Person')->remove(array());
        $this->dm->flush();
    }

    public function testSavePerson()
    {
        $person = new Person();
        
        $name = 'John Smith';
        $person->setName($name);

        $person = $this->personService->savePerson($person);

        $this->assertNotNull($person->getId());
    }

    public function testFindPersonById()
    {
        $person = new Person();

        $name = 'John Smith';
        $person->setName($name);
        
        $person = $this->personService->savePerson($person);

        $this->assertEquals($person, $this->personService->findPersonById($person->getId()));
    }

    public function testUpdatePerson()
    {
        $personJohn = new Person();
        $nameJohn = 'John Smith';
        $personJohn->setName($nameJohn);

        $personBob = new Person();
        $nameBob = 'Bob Clark';
        $personBob->setName($nameBob);

        $personJohn = $this->personService->savePerson($personJohn);
        $personBob = $this->personService->savePerson($personBob);

        $roleActor = new Role();
        $codActor = 'actor';
        $roleActor->setCod($codActor);

        $rolePresenter = new Role();
        $codPresenter = 'presenter';
        $rolePresenter->setCod($codPresenter);

        $this->dm->persist($roleActor);
        $this->dm->persist($rolePresenter);
        $this->dm->flush();

        $mm1 = new MultimediaObject();
        $title1 = 'Multimedia Object 1';
        $mm1->setTitle($title1);
        $mm1->addPersonWithRole($personJohn, $roleActor);
        $mm1->addPersonWithRole($personBob, $roleActor);
        $mm1->addPersonWithRole($personJohn, $rolePresenter);

        $mm2 = new MultimediaObject();
        $title2 = 'Multimedia Object 2';
        $mm2->setTitle($title2);
        $mm2->addPersonWithRole($personJohn, $roleActor);
        $mm2->addPersonWithRole($personBob, $rolePresenter);
        $mm2->addPersonWithRole($personJohn, $rolePresenter);

        $mm3 = new MultimediaObject();
        $title3 = 'Multimedia Object 3';
        $mm3->setTitle($title3);
        $mm3->addPersonWithRole($personJohn, $roleActor);

        $this->dm->persist($mm1);
        $this->dm->persist($mm2);
        $this->dm->persist($mm3);
        $this->dm->flush();
      
        $this->assertNull($this->personService->findPersonById($personJohn->getId())->getEmail());
        $this->assertNull($this->personService->findPersonById($personBob->getId())->getEmail());
        $this->assertNull($mm1->getPersonWithRole($personJohn, $roleActor)->getEmail());
        $this->assertNull($mm1->getPersonWithRole($personJohn, $rolePresenter)->getEmail());
        $this->assertNull($mm1->getPersonWithRole($personBob, $roleActor)->getEmail());
        $this->assertNull($mm2->getPersonWithRole($personJohn, $roleActor)->getEmail());
        $this->assertNull($mm2->getPersonWithRole($personBob, $rolePresenter)->getEmail());
        $this->assertNull($mm2->getPersonWithRole($personJohn, $rolePresenter)->getEmail());
        $this->assertNull($mm3->getPersonWithRole($personJohn, $roleActor)->getEmail());
        
        $emailJohn = 'johnsmith@mail.com';
        $personJohn->setEmail($emailJohn);

        $personJohn = $this->personService->updatePerson($personJohn);

        $this->assertEquals($emailJohn, $this->personService->findPersonById($personJohn->getId())->getEmail());
        $this->assertNull($this->personService->findPersonById($personBob->getId())->getEmail());
        $this->assertEquals($emailJohn, $mm1->getPersonWithRole($personJohn, $roleActor)->getEmail());
        $this->assertEquals($emailJohn, $mm1->getPersonWithRole($personJohn, $rolePresenter)->getEmail());
        $this->assertNull($mm1->getPersonWithRole($personBob, $roleActor)->getEmail());
        $this->assertEquals($emailJohn, $mm2->getPersonWithRole($personJohn, $roleActor)->getEmail());
        $this->assertNull($mm2->getPersonWithRole($personBob, $rolePresenter)->getEmail());
        $this->assertEquals($emailJohn, $mm2->getPersonWithRole($personJohn, $rolePresenter)->getEmail());
        $this->assertEquals($emailJohn, $mm3->getPersonWithRole($personJohn, $roleActor)->getEmail());

        $emailBob = 'bobclark@mail.com';
        $personBob->setEmail($emailBob);

        $personBob = $this->personService->updatePerson($personBob);

        $this->assertEquals($emailJohn, $this->personService->findPersonById($personJohn->getId())->getEmail());
        $this->assertEquals($emailBob, $this->personService->findPersonById($personBob->getId())->getEmail());
        $this->assertEquals($emailJohn, $mm1->getPersonWithRole($personJohn, $roleActor)->getEmail());
        $this->assertEquals($emailJohn, $mm1->getPersonWithRole($personJohn, $rolePresenter)->getEmail());
        $this->assertEquals($emailBob, $mm1->getPersonWithRole($personBob, $roleActor)->getEmail());
        $this->assertEquals($emailJohn, $mm2->getPersonWithRole($personJohn, $roleActor)->getEmail());
        $this->assertEquals($emailBob, $mm2->getPersonWithRole($personBob, $rolePresenter)->getEmail());
        $this->assertEquals($emailJohn, $mm2->getPersonWithRole($personJohn, $rolePresenter)->getEmail());
        $this->assertEquals($emailJohn, $mm3->getPersonWithRole($personJohn, $roleActor)->getEmail());
    }

    public function testFindSeriesWithPerson()
    {
        $series1 = new Series();
        $title1 = 'Series 1';
        $series1->setTitle($title1);

        $series2 = new Series();
        $title2 = 'Series 2';
        $series2->setTitle($title2);

        $series3 = new Series();
        $title3 = 'Series 3';
        $series3->setTitle($title3);

        $this->dm->persist($series1);
        $this->dm->persist($series2);
        $this->dm->persist($series3);

        $personJohn = new Person();
        $nameJohn = 'John Smith';
        $personJohn->setName($nameJohn);

        $personBob = new Person();
        $nameBob = 'Bob Clark';
        $personBob->setName($nameBob);

        $personKate = new Person();
        $nameKate = 'Kate Simmons';
        $personKate->setName($nameKate);        

        $this->dm->persist($personJohn);
        $this->dm->persist($personBob);
        $this->dm->persist($personKate);

        $roleActor = new Role();
        $codActor = 'actor';
        $roleActor->setCod($codActor);

        $rolePresenter = new Role();
        $codPresenter = 'presenter';
        $rolePresenter->setCod($codPresenter);

        $this->dm->persist($roleActor);
        $this->dm->persist($rolePresenter);
        $this->dm->flush();

        $mm11 = new MultimediaObject();
        $title11 = 'Multimedia Object 11';
        $mm11->setTitle($title11);
        $mm11->addPersonWithRole($personJohn, $roleActor);
        $mm11->addPersonWithRole($personBob, $roleActor);
        $mm11->addPersonWithRole($personJohn, $rolePresenter);

        $mm12 = new MultimediaObject();
        $title12 = 'Multimedia Object 12';
        $mm12->setTitle($title12);
        $mm12->addPersonWithRole($personBob, $roleActor);
        $mm12->addPersonWithRole($personBob, $rolePresenter);

        $mm13 = new MultimediaObject();
        $title13 = 'Multimedia Object 13';
        $mm13->setTitle($title13);
        $mm13->addPersonWithRole($personKate, $roleActor);

        $mm21 = new MultimediaObject();
        $title21 = 'Multimedia Object 21';
        $mm21->setTitle($title21);
        $mm21->addPersonWithRole($personKate, $rolePresenter);
        $mm21->addPersonWithRole($personKate, $roleActor);

        $mm31 = new MultimediaObject();
        $title31 = 'Multimedia Object 31';
        $mm31->setTitle($title31);
        $mm31->addPersonWithRole($personJohn, $rolePresenter);

        $mm32 = new MultimediaObject();
        $title32 = 'Multimedia Object 32';
        $mm32->setTitle($title32);
        $mm32->addPersonWithRole($personJohn, $roleActor);
        $mm32->addPersonWithRole($personBob, $roleActor);
        $mm32->addPersonWithRole($personJohn, $rolePresenter);

        $this->dm->persist($mm11);
        $this->dm->persist($mm12);
        $this->dm->persist($mm13);
        $this->dm->persist($mm21);
        $this->dm->persist($mm31);
        $this->dm->persist($mm32);
        $this->dm->flush();

        $series1->addMultimediaObject($mm11);
        $series1->addMultimediaObject($mm12);
        $series1->addMultimediaObject($mm13);

        $series2->addMultimediaObject($mm21);

        $series3->addMultimediaObject($mm31);
        $series3->addMultimediaObject($mm32);

        $this->dm->persist($series1);
        $this->dm->persist($series2);
        $this->dm->persist($series3);

        $this->dm->persist($mm11);
        $this->dm->persist($mm12);
        $this->dm->persist($mm13);
        $this->dm->persist($mm21);
        $this->dm->persist($mm31);
        $this->dm->persist($mm32);

        $this->dm->flush();

        $seriesJohn = $this->personService->findSeriesWithPerson($personJohn);
        $seriesBob = $this->personService->findSeriesWithPerson($personBob);
        $seriesKate = $this->personService->findSeriesWithPerson($personKate);

        $this->assertEquals(2, count($seriesJohn));
        $this->assertEquals(2, count($seriesBob));
        $this->assertEquals(2, count($seriesKate));

        $this->assertEquals(array($series1, $series3), $seriesJohn->toArray());
        $this->assertEquals(array($series1, $series3), $seriesBob->toArray());
        $this->assertEquals(array($series1, $series2), $seriesKate->toArray());
    }

    public function testCreateRelationPerson()
    {
        $roleActor = new Role();
        $codActor = 'actor';
        $roleActor->setCod($codActor);

        $this->dm->persist($roleActor);
        $this->dm->flush();

        $mm = new MultimediaObject();
        $title = 'Multimedia Object';
        $mm->setTitle($title);

        $personJohn = new Person();
        $nameJohn = 'John Smith';
        $personJohn->setName($nameJohn);

        $this->assertEquals(0, count($mm->getPeopleInMultimediaObjectByRole($roleActor)));

        $mm = $this->personService->createRelationPerson($personJohn, $roleActor, $mm);

        $this->assertEquals(1, count($mm->getPeopleInMultimediaObjectByRole($roleActor)));
    }

    public function testAutoCompletePeopleByName()
    {
        $this->assertEquals(0, count($this->personService->autoCompletePeopleByName('john')));

        $personJohn = new Person();
        $nameJohn = 'John Smith';
        $personJohn->setName($nameJohn);

        $personBob = new Person();
        $nameBob = 'Bob Clark';
        $personBob->setName($nameBob);

        $personKate = new Person();
        $nameKate = 'Kate Simmons';
        $personKate->setName($nameKate);        

        $personBobby = new Person();
        $nameBobby = 'Bobby Weissmann';
        $personBobby->setName($nameBobby);

        $this->dm->persist($personJohn);
        $this->dm->persist($personBob);
        $this->dm->persist($personKate);
        $this->dm->persist($personBobby);
        $this->dm->flush();

        $this->assertEquals(1, count($this->personService->autoCompletePeopleByName('john')));
        $this->assertEquals($personJohn, $this->personService->autoCompletePeopleByName('john')[0]);

        $this->assertEquals(2, count($this->personService->autoCompletePeopleByName('bob')));
        $this->assertEquals(array($personBob, $personBobby), $this->personService->autoCompletePeopleByName('bob'));

        $this->assertEquals(1, count($this->personService->autoCompletePeopleByName('kat')));
        $this->assertEquals($personKate, $this->personService->autoCompletePeopleByName('kat')[0]);

        $this->assertEquals(2, count($this->personService->autoCompletePeopleByName('sm')));
        $this->assertEquals(array($personJohn, $personBobby), $this->personService->autoCompletePeopleByName('sm'));
    }
}