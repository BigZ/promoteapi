<?php

namespace App\DataFixtures;

use App\Entity\Artist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ArtistFixtures extends Fixture implements DependentFixtureInterface
{
    const ARTIST_FIXTURES_PREFIX = 'artist_fixtures_';

    public function load(ObjectManager $manager)
    {
        foreach ($this->getArtists() as $data) {
            $artist = new Artist();
            $artist->setName($data['name']);
            $artist->setSlug($data['slug']);
            $artist->setBio($data['bio']);
            $artist->setCreatedAt(new \DateTime('now'));
            $artist->setUpdatedAt(new \DateTime('now'));

            foreach ($data['labels'] as $label) {
                $artist->addLabel(
                    $this->getReference(sprintf('%s%s', LabelFixtures::LABEL_FIXTURES_PREFIX, $label))
                );
            }

            $this->addReference(sprintf('%s%s', self::ARTIST_FIXTURES_PREFIX, $data['slug']), $artist);

            $manager->persist($artist);
        }

        $manager->flush();
    }

    private function getArtists(): array
    {
        return [
            [
                'name' => 'Bob Marley',
                'slug' => 'bob-marley',
                'bio' => 'Bob is a <b>reggae</b> legend',
                'createdBy' => 'user1',
                'labels' => ['island-records', 'tuff-gong'],
                'imageName' => 'bob-marley.jpg',
            ],
            [
                'name' => 'Peter Tosh',
                'slug' => 'peter-tosh',
                'bio' => 'Tosh is the bush doctor !',
                'createdBy' => 'user1',
                'labels' => ['tuff-gong'],
            ],
            [
                'name' => 'Daft Punk',
                'slug' => 'daftpunk',
                'bio' => 'The robot musicians',
                'createdBy' => 'user2',
                'labels' => ['ninja-tune'],
            ],
            [
                'name' => 'Maitre Gims',
                'slug' => 'maitregims',
                'bio' => 'Aka Gandhi Djuna de Kinshasa',
                'createdBy' => 'user3',
                'labels' => ['wati-b'],
            ],
        ];
    }

    public function getDependencies()
    {
        return [
            LabelFixtures::class,
        ];
    }
}
