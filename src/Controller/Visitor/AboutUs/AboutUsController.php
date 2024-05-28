<?php

namespace App\Controller\Visitor\AboutUs;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AboutUsController extends AbstractController
{
    #[Route('/about/us', name: 'app_about_us')]
    public function index(): Response
    {
        return $this->render('pages/visitor/about_us/index.html.twig');
    }
}
