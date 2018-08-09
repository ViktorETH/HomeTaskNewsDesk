<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
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
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $passwordPlain = 'user';
        $roles = ['ROLE_USER'];

        $encoder = $this->container->get('security.password_encoder');
        $passwordEncoded = $encoder->encodePassword($userAdmin, $passwordPlain);

        $userAdmin->setName('user');
        $userAdmin->setSurname('general');
        $userAdmin->setAge(25);
        $userAdmin->setGender('male');

        $userAdmin->setEmail('user@example.com');
        $userAdmin->setPassword($passwordEncoded);
        $userAdmin->setRoles($roles);

        $manager->persist($userAdmin);
        $manager->flush();
    }


}