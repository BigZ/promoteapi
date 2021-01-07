<?php

namespace App\DataFixtures;

use App\Entity\Label;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LabelFixtures extends Fixture
{
    const LABEL_FIXTURES_PREFIX = 'label_fixtures_';

    public function load(ObjectManager $manager)
    {
        foreach ($this->getLabels() as $data) {
            $label = new Label();
            $label->setName($data['name']);
            $label->setSlug($data['slug']);
            $label->setDescription($data['description']);
            $label->setCreatedAt(new \DateTime('now'));
            $label->setUpdatedAt(new \DateTime('now'));

            $this->addReference(sprintf('%s%s', self::LABEL_FIXTURES_PREFIX, $data['slug']), $label);

            $manager->persist($label);
        }

        $manager->flush();
    }

    private function getLabels(): array
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
            ],
            [
                'name' => 'Ninja Tune',
                'slug' => 'ninja-tune',
                'description' => 'Black hooded sounds',
            ],
            [
                'name' => 'Wati B',
                'slug' => 'wati-b',
                'description' => 'Le label du rap francais',
            ],
        ];
    }
}
