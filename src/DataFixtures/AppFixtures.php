<?php

namespace App\DataFixtures;

use App\Entity\MachineOutil;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private UserPasswordEncoderInterface $userPasswordEncoder;

    /**
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        // création des utilisateurs
        $users = [];
        for($i = 0; $i < 3; $i++){
            $user = User::create(
                sprintf("test%d@api.com", $i),
                sprintf("name%d", $i)
            );
            $user->setPassword($this->userPasswordEncoder->encodePassword($user, "bonjour"));
            $manager->persist($user);

            $users[] = $user;
        }


        // création des machines
        foreach($users as $user){
            $k = 0;
            for($j = 0; $j < 2; $j++){
                shuffle($users);
                $faker = Faker\Factory::create("fr_FR");
                $machine = MachineOutil::create(
                    sprintf("machine%d", ++$k),
                    $faker->text(),
                    $users[array_rand($users)]
                );
                $manager->persist($machine);
            }

        }

        $manager->flush();
    }
}
