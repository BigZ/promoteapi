<?php

/*
 * This file is part of the promote-api package.
 *
 * (c) Bigz
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Artist;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class LoadArtistData
 * @author Romain Richard
 */
class LoadArtistData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @inheritdoc
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->getArtists() as $data) {
            $artist = new Artist();
            $artist->setCreatedBy($manager->getRepository('AppBundle:User')->findOneByUsername($data['createdBy']));
            $artist->setName($data['name']);
            $artist->setSlug($data['slug']);
            $artist->setBio($data['bio']);

            foreach ($data['labels'] as $label) {
                $artist->addLabel($manager->getRepository('AppBundle:Label')->findOneBySlug($label));
            }

            if (isset($data['imageFile'])) {
                $file = new UploadedFile(
                    $this->getImageFixtureDir().$data['imageFile'],
                    $data['imageFile'],
                    null,
                    null,
                    null,
                    true
                );
                $artist->setImageFile($file);
            }

            $manager->persist($artist);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 3;
    }

    /**
     * @return string
     */
    protected function getImageFixtureDir()
    {
        return __DIR__.'/../Images/Artist/';
    }

    /**
     * @return array
     */
    private function getArtists()
    {
        return [
            [
                'name' => 'Bob Marley',
                'slug' => 'bob-marley',
                'bio' => 'Bob is a <b>reggae</b> legend',
                'createdBy' => 'user1',
                'labels' => ['island-records', 'tuff-gong'],
                'imageFile' => 'bob-marley.jpg',
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
}
