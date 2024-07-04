<?php

namespace App\Controller\Admin\Order;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\BasketRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class OrderAdminController extends AbstractController
{
    #[Route('/order', name: 'app_order_admin_index', methods: ['GET'])]
    public function index(OrderRepository $orderRepository): Response
    {

       $order = $orderRepository->findAll();
       
       
        return $this->render('pages/admin/order/index.html.twig', [
            'orders' => $order
        ]);
    }

    

    #[Route('/order/{id}/show', name: 'app_order_admin_show', methods: ['GET'])]
    public function show(BasketRepository $orderRepository, Order $order, int $id): Response
    {

        $idOrder = $id;
        $basket = $orderRepository->findBy(['theOrder' => $idOrder]);
        
        // dd($basket);


        return $this->render('pages/admin/order/show.html.twig', [
            'order' => $order,
            'product' => $basket
        ]);
    }

   

    #[Route('/{id}', name: 'app_order_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Order $order, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid("delete_order_{$order->getId()}", $request->request->get('_csrf_token') )) {
            $entityManager->remove($order);
            $entityManager->flush();
            $this->addFlash("success", "La commande N°{$order->getId()} a été supprimée avec succès");
        }

        return $this->redirectToRoute('app_order_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
