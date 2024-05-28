<?php

namespace App\Controller\Visitor\Store;

use App\Repository\StoreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StoreController extends AbstractController
{
    #[Route('/store', name: 'app_store_index')]
    public function index(StoreRepository $storeRepository): Response
    {
        return $this->render('pages/visitor/store/index.html.twig', [
            'stores' => $storeRepository->findAll(),
        ]);
    }
}
