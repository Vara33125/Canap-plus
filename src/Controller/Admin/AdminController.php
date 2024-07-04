<?php


namespace App\Controller\Admin;

use App\Repository\CategoryRepository;
use App\Repository\ContactRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\StoreRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Gedmo\Tree\RepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/admin')]
class AdminController extends AbstractController
{
    public function __construct(private OrderRepository $orderRepository, private ProductRepository $productRepository,private CategoryRepository $categoryRepository ,private TagRepository $tagRepository ,private UserRepository $userRepository ,private StoreRepository $storeRepository,private ContactRepository $contactRepository)
    {

    }
    #[Route('/', name: 'app_admin_home', methods: ['GET'])]
    public function index(): Response
    {
        $categories = $this->categoryRepository->findAll();
        $nbrcat = count($categories);
       
        $products = $this->productRepository->findAll();
        $nbrProducts = count($products);

        $tags = $this->tagRepository->findAll();
        $nbrTag = count($tags);

        $users = $this->userRepository->findAll();
        $nbrUser = count($users);

        $stores = $this->storeRepository->findAll();
        $nbrStores = count($stores);

        $contacts = $this->contactRepository->findAll();
        $nbrContacts = count($contacts);

        $orders = $this->orderRepository->findAll();
        $nbrorders = count($orders);

        return $this->render('pages/admin/home/index.html.twig', [
            'categorie' => $nbrcat,
            'product' => $nbrProducts,
            'tag' => $nbrTag,
            'user' => $nbrUser,
            'store' => $nbrStores,
            'contact' => $nbrContacts,
            'order' => $nbrorders

        ]);
    }

    
}