<?php

namespace App\Controller;

use App\Entity\ParamType;
use App\Entity\ParamValue;
use App\Helper\CommonUtils;
use App\Repository\ParamTypeRepository;
use App\Repository\ParamValueRepository;
use App\Repository\ProductRepository;
use App\Service\ProductManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        /** @var array<string,int> $paramTypeMap */
        $paramTypeMap = CommonUtils::mapKV(
            $this->paramTypeRepository->findAll(),
            fn($_, ParamType $pt) => [$pt->getName(), $pt->getId() ]
        );
        /** @var array<string,int> $paramValuesMap */
        $paramValuesMap = CommonUtils::mapKV(
            $this->paramValueRepository->findAll(),
            fn($_, ParamValue $pt) => [$pt->getStringValue(), $pt->getId() ]
        );
        $products = $this->productRepository->getByFilter(
            null,
            [
                $paramTypeMap['Záruka'] => [ $paramValuesMap['36 měsíců'], $paramValuesMap['48 měsíců'] ],
                $paramTypeMap['Velikost ciferníku'] => [ 'min' => 18, 'max' => 30 ],
            ],
            500,
            20000,
            0,
            100
        );
        return $this->render('home/index.html.twig', [
            'products' => $products,
        ]);
    }

    public function __construct(
        private ProductRepository $productRepository,
        private ParamTypeRepository $paramTypeRepository,
        private ParamValueRepository $paramValueRepository,
    )
    {
    }
}
