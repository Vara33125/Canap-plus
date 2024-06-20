<?php
namespace App\Controller\Visitor\Profile;

use App\Entity\User;
use App\Form\EditProfileFormType;
use App\Form\EditPasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile_index')]
    public function index(): Response
    {
        return $this->render('pages/visitor/profile/index.html.twig');
    }
    #[Route('/profile/edit', name: 'app_profile_edit', methods: ['GET' , 'POST'])]
    public function edit(Request $request , EntityManagerInterface $em) :Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileFormType::class, $user);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {
            
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "Votre profil a été mis a jour");

            return $this->redirectToRoute('app_profile_index');
        }
        return $this->render('pages/visitor/profile/edit.html.twig', [
                'form' => $form->createView()
        ]);
    }
    #[Route('/profile/edit/password', name: 'app_profile_edit_password', methods: ['GET' , 'POST'])]
    public function editPassword(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher) : Response
    {
        /** @var User*/
        $user = $this->getUser();

        
        $form = $this->createForm(EditPasswordFormType::class, null);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid() ) {
            

            $newPassword = $form->get('password')->getData();
            
            $passwordHashed = $hasher->hashPassword($user, $newPassword);
            $user->setPassword($passwordHashed);
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "Votre mot de passe a été mis a jour");

            return $this->redirectToRoute('app_profile_index');
        }
        return $this->render('pages/visitor/profile/edit-password.html.twig', [
            'form' => $form->createView()
    ]);
    }
    #[Route('/profile/delete', name: 'app_profile_delete', methods: ['GET' , 'POST'])]
    public function delete(EntityManagerInterface $em , Request $request) : Response
    {   
            $user = $this->getUser();
            
            $em->remove($user);
            $em->flush();
            $this->addFlash("success", "Votre compte a été supprimé avec succès");
        
        return $this->redirectToRoute('visitor_app_register');
    }
}
