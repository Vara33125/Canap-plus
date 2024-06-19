<?php
namespace App\Service;

use App\Repository\ProductRepository;

    class SearchService
    {

        public function __construct(private ProductRepository $productRepository)
        {

        }
        public function searchProduct(string $keywords) :array
        {
           return $this->productRepository->findProductSearch($keywords);
        }
    }