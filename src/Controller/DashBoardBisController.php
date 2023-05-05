<?php

namespace App\Controller;

use App\Entity\Duck;
use App\Form\DuckType;
use App\Repository\DuckRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DashBoardBisController extends AbstractController
{

//
//    #[Route('/dashboard/{id}/edit', name: 'app_dashboard_edit', methods: ['GET', 'POST'])]
//    public function edit(Request $request, Duck $duck, DuckRepository $duckRepository): Response
//    {
//        $form = $this->createForm(DuckType::class, $duck);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $duckRepository->save($duck, true);
//
//            return $this->redirectToRoute('app_dashboard', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->render('/dashboard/edit.html.twig', [
//            'duck' => $duck,
//            'form' => $form,
//        ]);
//    }

//    #[Route('/{id}', name: 'app_dash_board_bis_delete', methods: ['POST'])]
//    public function delete(Request $request, Duck $duck, DuckRepository $duckRepository): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$duck->getId(), $request->request->get('_token'))) {
//            $duckRepository->remove($duck, true);
//        }
//
//        return $this->redirectToRoute('app_dash_board_bis_index', [], Response::HTTP_SEE_OTHER);
//    }
}
