<?php

namespace App\Controller\Visitor\Product;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductClientController extends AbstractController
{
   
    #[Route('/product', name: 'app_product_client', methods:['GET'])]
    public function index(ProductRepository $productRepository ,CategoryRepository $cr): Response
    {    
        
        return $this->render('pages/visitor/product_client/index.html.twig', [
            "products" => $productRepository->findBy(["isPublished" => true])
        ]);
    }
    #[Route('/product/detail/{slug}', name: 'app_product_client_detail', methods:['GET'])]
    public function detail(Product $product) : Response
    {
            return $this->render('pages/visitor/product_client/detail.html.twig',[
                "product" => $product
            ]);
    }
}
