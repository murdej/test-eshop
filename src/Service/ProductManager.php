<?php

namespace App\Service;

use App\Entity\ParamValue;
use App\Entity\Product;
use App\Helper\CommonUtils;
use App\Repository\ParamValueRepository;
use App\Repository\ProductRepository;

class ProductManager
{
    public function __construct(
        protected ProductRepository $productRepository,
        protected ParamValueRepository $paramValueRepository,
    )
    {
    }

    /**
     * @param Product $product
     * @param array<int,int|float|null|string> $values
     * @return void
     */
    public function setProductParamValues(Product $product, array $values): void
    {
        $diffs = CommonUtils::diffAssoc(
            CommonUtils::mapKV(
                $product->getParamValues()->toArray(),
                fn($_, ParamValue $paramValue) => [ $paramValue, $paramValue->getNumberValue() ?? $paramValue->getStringValue() ]
            ),
            $values,
            fn(ParamValue $oldValue, string|int|float $newValue) => $oldValue->getStringValue() == $newValue || $oldValue->getNumberValue() == $newValue
        );
        foreach ($diffs->removed as $item) $product->removeParamValue($item->oldValue);
        foreach ($diffs->added as $paramTypeId => $item) $product->addParamValue($this->paramValueRepository->findOrCreate($paramTypeId, $item->newValue));
        foreach ($diffs->changed as $paramTypeId => $item) {
            $product->removeParamValue($item->oldValue);
            $product->addParamValue($this->paramValueRepository->findOrCreate($paramTypeId, $item->newValue));
        }
    }
}