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

use AppBundle\Entity\Label;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadLabelData
 * @author Romain Richard
 */
class LoadLabelData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @inheritdoc
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->getLabels() as $data) {
            $label = new Label();
            $label->setCreatedBy($manager->getRepository('AppBundle:User')->findOneByUsername($data['createdBy']));
            $label->setName($data['name']);
            $label->setSlug($data['slug']);
            $label->setDescription($data['description']);
            $label->setCreatedAt(new \DateTime('now'));
            $label->setUpdatedAt(new \DateTime('now'));

            $manager->persist($label);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }

    /**
     * @return array
     */
    private function getLabels()
    {
        return [
            [
                'name' => 'Island Records',
                'slug' => 'island-records',
                'description' => 'Music from the tropics',
                'createdBy' => 'user1',
            ],
            [
                'name' => 'Tuff Gong',
                'slug' => 'tuff-gong',
                'description' => 'Music from the ghetto',
                'createdBy' => 'user1',
            ],
            [
                'name' => 'Ninja Tune',
                'slug' => 'ninja-tune',
                'description' => 'Black hooded sounds',
                'createdBy' => 'user2',
            ],
            [
                'name' => 'Wati B',
                'slug' => 'wati-b',
                'description' => 'Le label du rap francais',
                'createdBy' => 'user3',
            ],
        ];
    }
}
