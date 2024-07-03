<?php

namespace App\Controller\Visitor\Payment;


use App\Entity\Order;
use App\Service\OrderService;
use App\Service\StripeService;

use App\Security\EmailVerifier;

use App\Repository\OrderRepository;
use Symfony\Component\Mime\Address;
use App\Service\Basket\BasketService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/payment')]
class PaymentController extends AbstractController
{

    public function __construct(
        private OrderService $orderPersisterService,
        private OrderRepository $orderRepository,
        private UrlGeneratorInterface $urlGenerator,
        private EntityManagerInterface $em,
        private BasketService $basketService,
        private StripeService $stripeService
    )
    {
    } 

    #[Route('/checkout/{id}', name: 'app_checkout', methods:['GET'])]
    public function index($id): Response
    { 

        $order = $this->orderRepository->find($id);

        if ( ! $order ) 
        {
            return $this->redirectToRoute("visitor.cart.index");
        }

        $items = $order->getBaskets()->toArray();
        $data = [];

        foreach ($items as $item) 
        {
            $data[] = [
                'price_data' => [
                    'currency' => 'EUR',
                    'unit_amount' => $item->getProduct()->getPrice() * 100,
                    'product_data' => [
                        'name' => $item->getProduct()->getName(),
                        
                    ]
                ],
                'quantity' => $item->getQuantity() 
            ];
        }

        $stripeSecretKey = $this->stripeService->getStripeApiSecret();

        \Stripe\Stripe::setApiKey($stripeSecretKey);

        /**
         * @var \App\Entity\User
         */
        $user = $this->getUser();

        $checkout_session = \Stripe\Checkout\Session::create([
            'customer_email' => $user->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [
                $data
            ],
            'mode' => 'payment',
            'success_url' => "https://localhost:8000/payment/{$order->getId()}/success",
            'cancel_url'  => "https://localhost:8000/payment/{$order->getId()}/cancel",

            
        ]);

       
        return new RedirectResponse($checkout_session->url);
    }

    #[Route('/{id<\d+>}/success', name: 'app_checkout_success', methods:['GET'])]
    public function success(Order $order , EmailVerifier $emailVerifier, Security $security): Response
    {

        $order->setPaid(true);

        $this->em->persist($order);
        $this->em->flush();

       
        $emailVerifier->sendEmailConfirmation('app_verify_email', $security->getUser(),
        (new TemplatedEmail())
            ->from(new Address('canap-plus@gmail.com', 'Canap\'plus'))
            ->to($order->getUser()->getEmail())
            ->subject('Confirmation de votre commande')
            ->htmlTemplate('pages/visitor/order/confirmation_order.html.twig')
    );
        // Vider le panier
        $this->basketService->emptyBasket();

        return $this->redirectToRoute('app_product_client');
    }
    
    #[Route('/{id<\d+>}/cancel', name: 'app_checkout_cancel', methods:['GET'])]
    public function cancel(): Response
    {

        

        return $this->render("pages/visitor/payment/cancel.html.twig");
    }
}
