<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\ParamType;
use App\Entity\ParamTypeType;
use App\Entity\Product;
use App\Helper\CommonUtils;
use App\Service\ProductManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $brands =   [];
        foreach (['Prim', 'Rolex', 'Omega', 'Tag Heuer', 'Seiko'] as $brandName) {
            $brand = new Brand();
            $brand->setName($brandName);
            $brand->setSlug(commonUtils::slugify($brandName));
            $brands[] = $brand;
            $manager->persist($brand);
        }

        /** @var ParamType[] $paramTypes */
        $paramTypes = [];
        foreach ([
            ['dialSize', 'Velikost ciferníku', ParamTypeType::Number, 'mm'],
            ['color', 'Barva', ParamTypeType::Color, ''],
            ['warranty', 'Záruka', ParamTypeType::Numbering, ''],
            ['dialType', 'Ciferník', ParamTypeType::Numbering, ''],
            ['waterproof', 'Vodotěsnost', ParamTypeType::Number, 'm'],
        ] as [$k, $name, $type, $unit]) {
            $paramType = new ParamType();
            $paramType->setName($name);
            $paramType->setType($type);
            $paramType->setUnit($unit);
            $paramTypes[$k] = $paramType;
            $manager->persist($paramType);
        }

        $rootCat = new Category();
        $rootCat->setName('Hodinářství');
        $rootCat->setSlug('budiky');
        $rootCat->setNumOrder(0);
        $manager->persist($rootCat);

        $manager->flush();

        $i = 0;
        $cats = [];
        $catParams = [];
        foreach ([
             ['Hodinky', [ 'dialSize', 'color', 'warranty', 'dialType', 'waterproof' ]],
             ['Kapesní hodinky', [ 'dialSize', 'color', 'warranty' ]],
             ['Budíky', [ 'color', 'warranty', 'dialType' ]],
        ] as [ $name, $paramTypeKeys ]) {
            $cat = new Category();
            $cat->setParentCategory($rootCat);
            $cat->setName($name);
            $cat->setSlug(commonUtils::slugify($name));
            $cat->setNumOrder($i++);
            foreach($paramTypeKeys as $k)
                $cat->addParamType($paramTypes[$k]);

            $cats[] = $cat;
            $manager->persist($cat);

            $manager->flush();

            $catParams[$cat->getId()] = $paramTypeKeys;
        }

        /* @var array<string, int[]|string[]> $sampleValues */
        $sampleValues = [
            'dialSize' => [15, 20, 25, 30, 35],
            'color' => ['červená', 'zelená', 'zlatá', 'černá', 'bronz', 'ocel', 'bílá'],
            'warranty' => [ '24 měsíců', '36 měsíců', '48 měsíců'],
            'dialType' => [ 'Analogový', 'Digitální', 'Kombinace' ],
            'waterproof' => [ 2, 5, 7, 10, 15, 20 ],
        ];

        $i = 0;
        foreach ([
            'Eclipse Chrono', 'AstraLux', 'TitanWave', 'Aurora Timepiece', 'NovaElite', 'Stellar Quartz', 'Celestial Gear', 'Infinity Dial', 'Quantum Chronograph', 'LunarSync', 'ZenithX', 'Nebula Watch', 'Orion Precision', 'Solaris Elite', 'ChronoBlade', 'StarGazer', 'TimeMaster', 'Eon Watch', 'AstroFusion', 'SpectraLux', 'Galactic Chrono', 'Tempus Nova', 'Vortex Timepiece', 'Polaris Precision', 'Nimbus Chrono', 'Phoenix Dial', 'Titanium Edge', 'Helios Watch', 'Atlas Timer', 'Comet Chronograph', 'Eclipse Radiance', 'Cygnus Watch', 'Hyperion Timepiece', 'Horizon Chrono', 'Mirage Watch', 'Orbital Dial', 'ChronoStar', 'Celeste Timer', 'Pioneer Chrono', 'Cosmos Elite', 'Ascendant Watch', 'Horizon Lux', 'Skyline Timer', 'AstraLux Pro', 'OmniChrono', 'Galileo Watch', 'Spectre Dial', 'Voyager Timepiece', 'Infinite Horizon', 'ExoChrono',
        ] as $productName) {
            $product = new Product();
            $product->setName($productName);
            $product->setSlug(CommonUtils::slugify($productName));
            $product->setCode((string)($i + 156000));
            $product->setNumOrder($i++);
            $product->setCategory(commonUtils::randomItem($cats));
            $product->setBrand(commonUtils::randomItem($brands));
            $product->setPrice((string)random_int(300, 30000));
            $product->setDescription('Bla bla');
            // echo "cat id = " . $product->getCategory()->getId(); exit();

            $manager->persist($product);
            $manager->flush();

            /** @var array<int,string|int> $params */
            $params = CommonUtils::mapKV(
                $catParams[$product->getCategory()->getId()],
                fn($k, $v) => [
                    $paramTypes[$v]->getId(),
                    CommonUtils::randomItem($sampleValues[$v])
                ]
            );
            
            $this->productManager->setProductParamValues(
                $product, $params
            );
        }
    }

    public function __construct(
        protected ProductManager $productManager
    )
    {
    }

}
