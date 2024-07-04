<?php

namespace App\Controller\Visitor;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'visitor_welcome_index', methods:['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('pages/visitor/welcome/index.html.twig', [
        "products" => $productRepository->findBy(["isNew" => true])
    ]);
    }
}
