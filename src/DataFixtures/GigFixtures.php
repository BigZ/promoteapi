<?php

namespace App\DataFixtures;

use App\Entity\Gig;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GigFixtures extends Fixture implements DependentFixtureInterface
{
    const GIG_FIXTURES_PREFIX = 'gig_fixtures_';

    public function load(ObjectManager $manager)
    {
        foreach ($this->getGigs() as $data) {
            $gig = new Gig();
            $gig->setCreatedAt($data['createdAt']);
            $gig->setStartDate($data['startDate']);
            $gig->setEndDate($data['endDate']);
            $gig->setVenue($data['venue']);
            $gig->setAddress($data['address']);
            $gig->setFacebookLink($data['facebookLink']);
            $gig->setName($data['name']);
            $gig->setCreatedAt(new \DateTime('now'));
            $gig->setUpdatedAt(new \DateTime('now'));

            foreach ($data['artist'] as $artistSlug) {
                $artist = $this->getReference(
                    sprintf('%s%s', ArtistFixtures::ARTIST_FIXTURES_PREFIX, $artistSlug)
                );
                $artist->addGig($gig);
                $manager->persist($artist);
            }

            $manager->persist($gig);
        }

        $manager->flush();
    }

    private function getGigs(): array
    {
        return [
            [
                'name' => 'One love peace concert',
                'startDate' => new \DateTime('1970-06-01T19:30:00'),
                'endDate' => new \DateTime('1970-06-02T02:00:00'),
                'venue' => 'Jamaica Stadium',
                'address' => 'nearby kingston',
                'facebookLink' => null,
                'artists' => ['bob-marley', 'peter-tosh'],
                'createdAt' => new \DateTime('yesterday'),
            ],
            [
                'name' => 'Alive 2007',
                'startDate' => new \DateTime('2007-03-05T21:30:00'),
                'endDate' => new \DateTime('2007-03-05T23:30:00'),
                'venue' => 'Bercy Arena',
                'address' => 'Quai de Bercy, Paris',
                'facebookLink' => 'https://www.facebook.com/events/981661548572560/',
                'artists' => ['daftpunk'],
                'createdAt' => new \DateTime('now'),
            ],
            [
                'name' => 'Paris 2015',
                'startDate' => new \DateTime('2015-04-05T21:30:00'),
                'endDate' => new \DateTime('2015-04-05T23:30:00'),
                'venue' => 'Zenith de Paris',
                'address' => 'Porte de pantin, paris',
                'facebookLink' => 'https://www.facebook.com/events/4212/',
                'artists' => ['maitregims'],
                'createdAt' => new \DateTime('now'),
            ],
            [
                'name' => 'Zenith de Lille 2015',
                'startDate' => new \DateTime('2015-04-07T21:30:00'),
                'endDate' => new \DateTime('2015-04-07T23:30:00'),
                'venue' => 'Zenith de Lille',
                'address' => 'Rue jean jaures, Lille',
                'facebookLink' => 'https://www.facebook.com/events/3456/',
                'createdBy' => 'user3',
                'artists' => ['maitregims'],
                'createdAt' => new \DateTime('now'),
            ],
        ];
    }

    public function getDependencies()
    {
        return [
            ArtistFixtures::class,
        ];
    }
}
