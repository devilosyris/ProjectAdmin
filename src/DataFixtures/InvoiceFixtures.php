<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Invoice;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class InvoiceFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create('fr_FR');
        
        for($i = 2; $i < 50; $i++){

            $invoice = new Invoice();
            $invoice->setName("Facture n°".$i);
            $invoice->setAmount($faker->randomFloat(2, 250, 5000));
            $invoice->setPaid($faker->boolean(50));
            $invoice->setPdf('unfichier.pdf');
            $invoice->setExpiry(new \DateTime('2020-05-13'));

            $manager->persist($invoice);

        }

        $manager->flush();
    }
}
