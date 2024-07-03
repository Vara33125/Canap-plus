<?php

namespace App\Controller\Visitor\Payment;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/payment',)]
class PaymentController extends AbstractController
{
    #[Route('/index', name: 'app_payment_index')]
    public function index(): Response
    {
        return $this->render('pages/visitor/payment/index.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }
}
