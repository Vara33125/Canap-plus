<?php
namespace App\Controller\Visitor\Contact;


use DateTimeImmutable;
use App\Entity\Contact;
use App\Form\ContactFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ContactUserController extends AbstractController
{
    
        private $security;
    
        public function __construct(Security $security)
        {
            $this->security = $security;
        }


    #[Route('/contact', name: 'app_contact_index')]
    public function index( Request $request, EntityManagerInterface $em): Response
    {
           
            $contact = new Contact();
            $form = $this->createForm(ContactFormType::class, $contact);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                

                
                $contact->setCreatedAt(New DateTimeImmutable());
                
                if ($this->getUser() == null) {
                   $contact->setGuest(true);
               

                }else{
                    $contact->setGuest(false);
                    $user = $this->security->getUser();
                    $contact->setAuthor($user);
                }
                $em->persist($contact);
                $em->flush();
    
                return $this->render('pages/visitor/contact/confirmation.html.twig');
            }
            return $this->render('pages/visitor/contact/index.html.twig', [
                
                'form' => $form,
            ]);
        
    }
   
}
