<?php
namespace App\Controller\Visitor\Order;

use App\Entity\Order;
use App\Form\OrderFormType;
use App\Service\OrderService;
use App\Service\Basket\BasketService;
use Symfony\Bundle\SecurityBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/order')]
class OrderController extends AbstractController
{

    public function __construct(
         private BasketService $basketService,
         private OrderService $orderService
    )
    {
        
    }


    #[Route('/index', name: 'app_order_index')]
    public function index(Security $security, Request $request): Response
    {   
        $order = new Order;


        $products = $this->basketService->getBasketItems();
        $prixTotal = $this->basketService->getBasketTotalAmount();

        if ( count($this->basketService->getBasket()) <= 0 ) {
            
            return $this->redirectToRoute('app_basket_index');
        }

        $form = $this->createForm(OrderFormType::class);

        $form->handleRequest($request);
        
        if ( $form->isSubmitted() && $form->isValid() ) {
            
            $store = $form->get('store')->getData();
            
            $order = $this->orderService->persist($store);
            
            

            return $this->redirectToRoute('app_checkout', [
                
                'id' => $order->getId()
                
            ]);
        }


        // Permet de rediriger l'utilisateur vers la page de connexion si l'utilisateur n'est pas connecté
        if ( $security->getUser() != null) {
          
            return $this->render('pages/visitor/order/index.html.twig', [
                "form" => $form,
                "product" =>$products,
                'Total' => $prixTotal
            ]);
        }else{
            return $this->redirectToRoute('visitor_app_login');
        }
        }
}
