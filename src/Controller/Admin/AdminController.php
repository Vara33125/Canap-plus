<?php


namespace App\Controller\Admin;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_home', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('pages/admin/home/index.html.twig');
    }
}

