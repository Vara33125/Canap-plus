<?php
namespace App\Controller\Admin\Contact;




use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class ContactAdminController extends AbstractController
{
    #[Route('/contact', name: 'app_contact_admin')]
    public function index( ContactRepository $contact): Response
    {
        $contacts = $contact->findAll();
        
        return $this->render('pages/admin/contact/index.html.twig', [
            'contacts' => $contacts
        ]);
    }

    #[Route('/contact/{id<\d+>}/delete', name: 'app_contact_admin_delete', methods: ['GET', 'POST'])]
    public function delete(Contact $contact, Request $request, EntityManagerInterface $em): Response
    {
        if ( $this->isCsrfTokenValid('delete_contact_'.$contact->getId(), $request->request->get('_csrf_token')) )
        {
            $this->addFlash('success', "La demande  N°{$contact->getId()} a été supprimée avec succès.");

            $em->remove($contact);

            $em->flush();
            
        }
        
        return $this->redirectToRoute('app_contact_admin');

    }
    
}
