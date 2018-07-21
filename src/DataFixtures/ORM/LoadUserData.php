<?php

/*
 * This file is part of the promote-api package.
 *
 * (c) Bigz
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace App\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadUserData
 * @author Romain Richard
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    const PASSWORD = 'test';

    /**
     * Some fixture users may be useful for tests, and need to be accessed separately.
     */
    const USER_ADMIN = [
        'username' => 'admin',
        'email' => 'admin@admin.com',
        'apiKey' => '123',
        'roles' => ['ROLE_ADMIN'],
    ];

    /**
     * @var ContainerInterface|null
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
        if (!$this->container) {
            return;
        }

        $encoder = $this->container->get('security.password_encoder');

        foreach ($this->getUsers() as $data) {
            $user = new User();

            foreach ($data as $fieldName => $fieldValue) {
                $setter = 'set'.ucfirst($fieldName);
                $user->$setter($fieldValue);
            }

            $password = $encoder->encodePassword($user, self::PASSWORD);
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
            self::USER_ADMIN,
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
