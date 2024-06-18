<?php

namespace App\Controller\Admin\Tag;



use App\Entity\Tag;
use App\Form\TagFormType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/tag/admin')]
class TagAdminController extends AbstractController
{
    #[Route('/', name: 'app_tag_admin_index', methods: ['GET'])]
    public function index(TagRepository $tagRepository): Response
    {
        return $this->render('pages/admin/tag/index.html.twig', [
            'tags' => $tagRepository->findAll(),
        ]);
    }

    #[Route('/create', name: 'app_tag_admin_create', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagFormType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tag);
            $entityManager->flush();

            return $this->redirectToRoute('app_tag_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pages/admin/tag/new.html.twig', [
            'tag' => $tag,
            'form' => $form,
        ]);
    }

    

    #[Route('/{id}/edit', name: 'app_tag_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tag $tag, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TagFormType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tag_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pages/admin/tag/edit.html.twig', [
            'tag' => $tag,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tag_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Tag $tag, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete_tag_'.$tag->getId(), $request->request->get('_csrf_token'))) {
            $entityManager->remove($tag);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tag_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
