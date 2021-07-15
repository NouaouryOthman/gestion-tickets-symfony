<?php

namespace App\DataFixtures;

use App\Entity\Role;
use App\Entity\Ticket;
use App\Entity\User;
use App\Entity\Priority;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder=$encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker='Faker\Factory'::create('fr_FR');
        //creation du role chef projet
        $chefRole= new Role();
        $chefRole->setTitle("CHEF");
        $manager->persist($chefRole);
        //creation du role client
        $clientRole = new Role();
        $clientRole->setTitle("CLIENT");
        $manager->persist($clientRole);
        //creation du role technicien
        $techRole= new Role();
        $techRole->setTitle("TECHNICIEN");
        $manager->persist($techRole);
        //creation des priorités
        $prtnormal = new Priority();
        $prtnormal->setTitle("Normal");
        $manager->persist($prtnormal);
        $prtmoy = new Priority();
        $prtmoy->setTitle("Moyen");
        $manager->persist($prtmoy);
        $prtfort = new Priority();
        $prtfort->setTitle("Fort");
        $manager->persist($prtfort);
        $d = new DateTime('today');
        //creation d'un chef de projet
        $chefuser=new User();
        $chefuser->setFirstname("Othman")
                  ->setLastname("Nouaoury")
                  ->setEmail("bouadi44@gmail.com")
                  ->setPassword($this->encoder->encodePassword($chefuser,'password'))
                  ->addUserrole($chefRole);
        $manager->persist($chefuser);
        //creation d'un technicien
        $usertech=new User();
        $hash=$this->encoder->encodePassword($usertech,'password');
        $usertech->setFirstname("Soufiane")
                 ->setLastname("Redouane")
                 ->setEmail("soufiane@ticketapp.ma")
                 ->setPassword($hash)
                 ->addUserRole($techRole);       
        $manager->persist($usertech);
        //creation d'un client    
        $userclient=new User();
        $hash=$this->encoder->encodePassword($userclient,'password');
        $userclient->setFirstname("Naoufal")
                   ->setLastname("Retard")
                   ->setEmail("naoufal@ticketapp.ma")
                   ->setPassword($hash)
                   ->addUserrole($clientRole);       
        $manager->persist($userclient);
        $manager->flush();
    }
}
