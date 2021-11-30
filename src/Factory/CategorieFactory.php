<?php

namespace App\Factory;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Categorie>
 *
 * @method static Categorie|Proxy createOne(array $attributes = [])
 * @method static Categorie[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Categorie|Proxy find(object|array|mixed $criteria)
 * @method static Categorie|Proxy findOrCreate(array $attributes)
 * @method static Categorie|Proxy first(string $sortedField = 'id')
 * @method static Categorie|Proxy last(string $sortedField = 'id')
 * @method static Categorie|Proxy random(array $attributes = [])
 * @method static Categorie|Proxy randomOrCreate(array $attributes = [])
 * @method static Categorie[]|Proxy[] all()
 * @method static Categorie[]|Proxy[] findBy(array $attributes)
 * @method static Categorie[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Categorie[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static CategorieRepository|RepositoryProxy repository()
 * @method Categorie|Proxy create(array|callable $attributes = [])
 */
final class CategorieFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [

        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Categorie $categorie) {})
        ;
    }

    protected static function getClass(): string
    {
        return Categorie::class;
    }
}
