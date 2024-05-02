<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $catNamess = ['Collection Hiver', 'Collection Été', 'Élégance & Style', 'Accessoires de Luxe', 'Haute Couture'];
        foreach ($catNamess as $name) {
            $cat = new Category();
            $cat->setName($name);
            $manager->persist($cat);
        }

        $manager->flush();
    }
}
