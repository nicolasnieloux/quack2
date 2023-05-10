<?php

namespace App\Controller;

use App\Entity\Quack;
use App\Form\QuackType;
use App\Repository\QuackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

//#[Route('/quack')]
class QuackController extends AbstractController
{
    #[Route('/', name: 'app_quack_index', methods: ['GET', 'POST'])]
    public function index(QuackRepository $quackRepository): Response
    {

        return $this->render('quack/index.html.twig', [
            'comments' =>$quackRepository->findAllComment(),
            'quacks' =>$quackRepository->findBy(['parent'=>0]),
        ]);
    }

    #[Route('/quack/new', name: 'app_quack_new', methods: ['GET', 'POST'])]
    public function new(Request $request, QuackRepository $quackRepository): Response
    {
        $user= $this->getUser();

        $quack = new Quack();
        $quack->setCreatedAt(new \DateTime());
        $quack->setDuck($user);
        $quack->setParent(0);

        $form = $this->createForm(QuackType::class, $quack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $quackRepository->save($quack, true);


            return $this->redirectToRoute('app_quack_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quack/new.html.twig', [
            'quack' => $quack,
            'form' => $form,
            'user' => $user,
        ]);
    }

    #[Route('/quack/{id}', name: 'app_quack_show', methods: ['GET'])]
    public function show(Quack $quack, QuackRepository $quackRepository): Response
    {
        $user= $this->getUser();


        return $this->render('quack/show.html.twig', [
            'comments' =>$quackRepository->findAllComment(),
            'quack' => $quack,
            'user' => $user,
        ]);
    }

    #[Route('/quack/{id}/edit', name: 'app_quack_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quack $quack, QuackRepository $quackRepository): Response
    {
        if ($this->getUser() !== $quack->getDuck()) {
            throw new AccessDeniedException('Tu fais quoi là!!!');
        }
        $form = $this->createForm(QuackType::class, $quack);
        $quack->setCreatedAt(new \DateTime());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $quackRepository->save($quack, true);

            return $this->redirectToRoute('app_quack_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quack/edit.html.twig', [
            'quack' => $quack,
            'form' => $form,
        ]);
    }

    #[Route('/quack/{id}', name: 'app_quack_delete', methods: ['POST'])]
    public function delete(Request $request, Quack $quack, QuackRepository $quackRepository): Response
    {
        if ($this->getUser() !== $quack->getDuck()) {
            throw new AccessDeniedException('Tu fais quoi là!!!');
        }
        if ($this->isCsrfTokenValid('delete' . $quack->getId(), $request->request->get('_token'))) {
            $quackRepository->remove($quack, true);
        }

        return $this->redirectToRoute('app_quack_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/quack/{id}/comment', name: 'app_quack_new_comment', methods: ['GET', 'POST'])]
    public function newComment(Request $request, QuackRepository $quackRepository, Quack $parent_quack): Response
    {
        $user= $this->getUser();

        $parent_id=$parent_quack->getId();

        $comment = new Quack();
        $comment->setCreatedAt(new \DateTime());
        $comment->setDuck($user);
        $comment->setParent($parent_id);


        $form = $this->createForm(QuackType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $quackRepository->save($comment, true);


            return $this->redirectToRoute('app_quack_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quack/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
            'user' => $user,
        ]);
    }

}
