<?php
namespace App\Controller\Visitor\SearchBar;



use App\Form\SearchProductFormType;
use App\Service\SearchService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchBarController extends AbstractController
{
    #[Route('/search/bar', name: 'app_search')]
    public function index(Request $request , SearchService $search): Response
    {
        $form = $this->createForm(SearchProductFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $data = $form->getData();
            $products = $search->searchProduct($data['keywords']);
           

            return $this->render('pages/visitor/search_bar/index.html.twig', [
                'products' => $products
            ]);
        }

    }

    public function getSearchBar() : Response
    {
      
        $form = $this->createForm(SearchProductFormType::class);

        return $this->render("components/_searchBar.html.twig", [
            'form' => $form->createView()
        ]);
    }
}
