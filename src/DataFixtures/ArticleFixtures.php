<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use app\Entity\Article;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i=0; $i < 10 ; $i++) { 
       
        $article = new Article;

        $article->setTitle("Titre de l'article n°$i")
                ->setContent("<p>Contnu de l'article n°$i</p>")
                ->setImage("http://placehold.it/350x150")
                ->setCreatedAt(new \DateTime);

        $manager->persist($article); // Fait persister la fixture Article

        }            


        $manager->flush(); // balance la requete SQL qui imprime la fixture sur la BDD
    }
}
