<?php
namespace App\Controller\Visitor\Basket;

use App\Repository\ProductRepository;
use App\Service\Basket\BasketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BasketController extends AbstractController
{
    public function __construct(private ProductRepository $productRepository, private BasketService $basketService)
    {

    }
    #[Route('/basket', name: 'app_basket_index')]
    public function index(): Response
    {
        $products = $this->basketService->getBasketItems();
        $prixTotal = $this->basketService->getBasketTotalAmount();
        return $this->render('pages/visitor/basket/index.html.twig', [
            'products' => $products,
            'Total' => $prixTotal
        ]);
    }

    #[Route('/basket/add/{id}', name: 'app_basket_add')]
    public function add(string $id) : Response
    {
        $product = $this->productRepository->find((int)$id);
        if ($product === null) 
        {
            throw $this->createNotFoundException("Produit non trouvé");
        }
        
        $this->basketService->add((int)$id);

        return $this->redirectToRoute('app_basket_index');
    }
    #[Route('/basket/decrement/{id}', name: 'app_basket_decrement')]
    public function decrement(string $id) : Response
    {
        $product = $this->productRepository->find((int)$id);
        if ($product === null) 
        {
            throw $this->createNotFoundException("Produit non trouvé");
        }
        
        $this->basketService->decrement((int) $id);

        return $this->redirectToRoute('app_basket_index');
    }
    #[Route('/basket/remove/{id}', name: 'app_basket_remove')]
    public function remove(string $id) : Response
    {
        $product = $this->productRepository->find((int)$id);
        if ($product === null) 
        {
            throw $this->createNotFoundException("Produit non trouvé");
        }
        
        $this->basketService->remove((int) $id);

        return $this->redirectToRoute('app_basket_index');
    }
}
