<?php


namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

abstract class BaseFixture extends Fixture
{
    /** @var ObjectManager */
    private $manager;
    /** @var Generator */
    protected $faker;

    // Méthode à implémenter par les classes enfant
    // dans laquelle générer les fausses données
    abstract protected function loadData(ObjectManager $manager);

    // Méthode imposée par Doctrine
    public function load(ObjectManager $manager)
    {
        // Enregistrement du Manager et instanciation de Faker
        $this->manager = $manager;
        $this->faker = Factory::create('fr_FR');

        // Appel de la méthode pour générer les données
        $this->loadData($manager);
    }

    /**
     * Créer plusieurs entités
     * @param int $count        nombre d'entités à créer
     * @param callable $factory fonction pour créer 1 entité
     */
    protected function createMany(int $count, callable $factory)
    {
        // Exécuter $factory $count fois
        for ($i = 0; $i < $count; $i++) {
            // La $factory doit retourner l'entité créée
            $entity = $factory($i);

            if ($entity === null) {
                throw new \LogicException('Tu as oublié de retourner l\'entité !!!');
            }

            // Avertir Doctrine pour l'enregistrement de l'entité
            $this->manager->persist($entity);
        }
    }
}