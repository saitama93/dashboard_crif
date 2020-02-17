<?php

namespace App\DataFixtures;

use App\Entity\Classroom;
use App\Entity\Intern;
use App\Entity\Session;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encode;

    public function __construct(UserPasswordEncoderInterface $encode)
    {
        $this->encode = $encode;
    }

    public function load(ObjectManager $em)
    {
        $faker = \Faker\Factory::create('fr_FR');
        $sexes = ['male', 'female'];
        $className = ['A1', 'A2', 'B1', 'B2', 'ILL'];

        for ($i = 0; $i < 5; $i++) {
            $session = new Session();
            $session->setName("Session n°" . $i);
            $em->persist($session);

            for ($j = 0; $j < 10; $j++) {
                $nameOfClasse = $faker->randomElement($className);
                $date_start = $faker->dateTimeBetween('0 days', '+90 days');
                $date_end = $faker->dateTimeBetween('+100 days', '+130 days');

                $classRoom = new Classroom();
                $classRoom->setName($nameOfClasse)
                    ->setCapacity(12)
                    ->setStartAt($date_start)
                    ->setEndAt($date_end)
                    ->setSession($session);
                $em->persist($classRoom);

                for ($k = 0; $k < mt_rand(9, 12); $k++) {
                    $sexe = $faker->randomElement($sexes);
                    $intern = new Intern();
                    $intern->setFirstName($faker->firstName($sexe))
                        ->setLastName($faker->lastName($sexe))
                        ->setEmail($faker->email())
                        ->setPhone($faker->phoneNumber())
                        ->setAge(mt_rand(18, 50))
                        ->setSexe($sexe)
                        ->setClassroom($classRoom);
                    $em->persist($intern);
                }
            }
        }

        $em->flush();
    }
}
