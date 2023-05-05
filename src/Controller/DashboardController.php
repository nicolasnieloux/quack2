<?php

namespace App\Controller;

use App\Entity\Duck;
use App\Form\DuckType;
use App\Repository\DuckRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function show(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        return $this->render('dashboard/show.html.twig', [
            'controller_name' => 'DashboardController',
            'duck' => $user,
        ]);
    }

    #[Route('/dashboard/{id}/edit', name: 'app_dashboard_edit', methods: ['GET', 'POST'])]
    public function edit(
        EntityManagerInterface $entityManager,
        int $id,
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        DuckRepository $duckRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $duck = $entityManager->getRepository(Duck::class)->find($id);
        $duck->setFirstname('');
        $duck->setLastname('');
        $duck->setPassword('');

        $form = $this->createFormBuilder($duck)
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('password', PasswordType::class)
            ->add('save', SubmitType::class, ['label' => 'Modify Profile'])
            ->getForm();
        $form->handleRequest($request);

      //  if($form->isSubmitted() && $form->isValid()) {
            // On enregistre le nouveau mot de passe en le hashant
            $duck->setPassword(
                $passwordHasher->hashPassword(
                    $duck,
                    $form->get('password')->getData()
                )
            );
            $entityManager->flush();

//            return $this->redirectToRoute('app_dashboard_edit');
 //       }

        return $this->render('dashboard/edit.html.twig', [
            'duck' => $duck,
            'form' => $form,
        ]);
    }
}

