<?php

namespace App\Controller\Visitor\Registration;

use App\Entity\User;
use DateTimeImmutable;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/register', name: 'visitor_app_register', methods:['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                    $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('canap-plus@gmail.com', 'Canap\'plus'))
                    ->to($user->getEmail())
                    ->subject('Confirmez votre email')
                    ->htmlTemplate('pages/visitor/registration/confirmation_email.html.twig')
            );

            // do anything else you need here, like send an email

            return $this->redirectToRoute('visitor_registraton_waiting_for_email_verification');
        }

        return $this->render('pages/visitor/registration/register.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/register/waiting-for-email-verification', name:'visitor_registraton_waiting_for_email_verification', methods: ['GET'])]
    public function waitingForEmailVerification(): Response
    {
        return $this->render('pages/visitor/registration/waiting_for_email_verification.html.twig');
    }

    #[Route('/politque_confidentialite', name: 'visitor_registration_politique')]
    public function politique() : Response
    {
        return $this->render('pages/visitor/registration/politique.html.twig');
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(EntityManagerInterface $entityManager,Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('visitor_app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('visitor_app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
            
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));
            
            return $this->redirectToRoute('visitor_app_register');
        }
        
        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $user->setVerifiedAt(new DateTimeImmutable());
        
        $entityManager->persist($user);

        // 9- Executons la requête
        $entityManager->flush();
        $this->addFlash('success', 'Votre email a bien été vérifier. Vous pouvez désormais vous connecter');
        
        return $this->redirectToRoute('visitor_welcome_index');
    }
}
