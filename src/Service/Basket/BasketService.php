<?php
namespace App\Service\Basket;

use App\Service\Basket\BasketItem;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

    class BasketService
    {
        public function __construct( private RequestStack $requestStack , private ProductRepository $productRepository)
        {
            
        }

        public function getBasket() :array
        {
            // on récupre les données stockés en session sous la clé "basket"
            return $this->requestStack->getSession()->get('basket', []);
        }
        
        public function setBasket(array $basket) : self
        {
            // on envoie en session les données du table basket sous la clé "basket"
            $this->requestStack->getSession()->set('basket', $basket);
            return $this;
        }

        public function add(int $id) : void
        {
            $basket = $this->getBasket();
            // On verifie si le produit existe dans le panier
            if ( \array_key_exists($id , $basket))
            {
                // on incrémente la quantitié de 1
                $basket[$id]++;    
            }else{
                // on attribue la valuer a 1
                $basket[$id] = 1;    
            }
            // on crée le panier en passant en parametre le tabelau "basket" dans la fonction créée au préalable
            $this->setBasket($basket);
        }
        public function getBasketItems() : array
        {
            // on initiliase le tableau 'basketItems'
            $basketitems = [];
            // on recupere le panier
            $basket = $this->getBasket();

            // on récupere le produit correspondant a son "id" et on le sauvegarde dans le tableau 'basketItems'
            foreach ($basket as $id => $quantity)
            {
                $product = $this->productRepository->find($id);
                //verifie que le produit existe en bdd
                if ( $product === null) {
                    continue;
                }

                $basketitems[] = new BasketItem($product , $quantity);

            }

            return $basketitems;
        }
        
        public function getBasketTotalAmount() : float
        {
            $basketItems = $this->getBasketItems();

            $totalAmount = 0;

            foreach ($basketItems as $basketItem)
            {
               $totalAmount += $basketItem->getAmount();
            }

            return $totalAmount;
        }
        public function decrement (int $id) :void
        {
            $basket = $this->getBasket();

            if (!array_key_exists($id, $basket)) {
                return;
            }
            
            if($basket[$id] ==1)
            {
                $this->remove($id);
                return;
            }

            $basket[$id]--;

            $this->setBasket($basket);
        }

        public function remove(int $id)
        {
            $basket = $this->getBasket();

            unset($basket[$id]);

            $this->setBasket($basket);

        }
}