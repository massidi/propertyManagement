<?php


namespace App\DataFixtures;


use App\Service\Commune;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CommuneKinshasFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $commune= new Commune();

        foreach ($commune->kinshasa() as $commune)
        {
            $kinshasa=new \App\Entity\Commune();

            $kinshasa->setNom($commune[0]);
            $manager->persist($kinshasa);
        }


        $manager->flush();

    }

}