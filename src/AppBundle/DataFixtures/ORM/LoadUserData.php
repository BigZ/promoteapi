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

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadUserData
 * @author Romain Richard
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @inheritdoc
     */
    public function load(ObjectManager $manager)
    {
        $encoder = $this->container->get('security.password_encoder');

        foreach ($this->getUsers() as $data) {
            $user = new User();

            foreach ($data as $fieldName => $fieldValue) {
                $setter = 'set'.ucfirst($fieldName);
                $user->$setter($fieldValue);
            }

            $password = $encoder->encodePassword($user, 'test');
            $user->setPassword($password);
            $manager->persist($user);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }

    /**
     * @return array
     */
    private function getUsers()
    {
        return [
            [
                'username' => 'admin',
                'email' => 'admin@admin.com',
                'apiKey' => '123',
                'roles' => ['ROLE_ADMIN'],
            ],
            [
                'username' => 'user1',
                'email' => 'user1@user.com',
                'apiKey' => '456',
                'roles' => ['ROLE_USER'],
            ],
            [
                'username' => 'user2',
                'email' => 'user2@user.com',
                'apiKey' => '789',
                'roles' => ['ROLE_USER'],
            ],
            [
                'username' => 'user3',
                'email' => 'user3@user.com',
                'apiKey' => '1011',
                'roles' => ['ROLE_USER'],
            ],
        ];
    }
}
