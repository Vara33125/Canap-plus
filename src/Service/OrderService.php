<?php
namespace App\Service;

use App\Entity\Basket;
use App\Entity\Order;
use App\Entity\Store;
use App\Service\Basket\BasketService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

    class OrderService
    {   


        private Order $order;
        
        public function __construct( private EntityManagerInterface $em , private Security $security, private BasketService $basketService)
        {
            
        }
        public function persist( Store $store)
        {
            $order = new Order ();


            
            $user = $this->security->getUser();

            $order
                ->setUser($user)
                ->setStore($store)
                ->setCreatedAt(new DateTimeImmutable())
                ->setOrderAmount($this->basketService->getBasketTotalAmount())
                ->setPaid(false)
            ;

            $this->em->persist($order);

            foreach ($this->basketService->getBasketItems() as $basketItem) {
                $basket =  new Basket();

                $basket
                    ->setTheOrder($order)
                    ->setProduct($basketItem->product)
                    ->setProductName($basketItem->product->getName())
                    ->setPrice($basketItem->product->getPrice())
                    ->setQuantity($basketItem->quantity)
                    ->setTotalAmount($basketItem->getAmount())
                ;
                
                $this->em->persist($basket);
            }

            $this->em->flush();


            return $order;
        }

        public function getOrder(): Order
        {
            return $this->order;
        }

        public function setOrder(Order $order) : void
        {
            $this->order = $order;
        }
    }