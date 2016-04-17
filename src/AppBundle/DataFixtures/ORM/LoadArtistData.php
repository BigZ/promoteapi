<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Artist;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadArtistData extends AbstractFixture implements OrderedFixtureInterface
{
    private function getArtists()
    {
        return [
            [
                'name' => 'Bob Marley',
                'slug' => 'bob-marley',
                'bio' => 'Bob is a <b>reggae</b> legend'
            ],
            [
                'name' => 'Daft Punk',
                'slug' => 'daftpunk',
                'bio' => 'The robot musicians'
            ],
        ];
    }
    
    public function load(ObjectManager $manager)
    {
        foreach ($this->getArtists() as $data) {
            $user = new Artist();

            foreach ($data as $fieldName => $fieldValue) {
                $setter = 'set'.ucfirst($fieldName);
                $user->$setter($fieldValue);
            }

            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}