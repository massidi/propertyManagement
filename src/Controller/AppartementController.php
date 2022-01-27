<?php

namespace App\Controller;

use App\Entity\Appartement;
use App\Form\AppartementType;
use App\Repository\AppartementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/appartement")
 */
class AppartementController extends AbstractController
{
    /**
     * @Route("/liste-appartement", name="appartement_index", methods={"GET"})
     * @param AppartementRepository $appartementRepository
     * @return Response
     */
    public function index(AppartementRepository $appartementRepository): Response
    {

        return $this->render('appartement/index.html.twig', [
            'appartements' => $appartementRepository->findAll(),
        ]);
    }

    /**
     * @Route("/ajouter-nouvel-appartement", name="appartement_new", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $appartement = new Appartement();
        $form = $this->createForm(AppartementType::class, $appartement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($appartement);
            $entityManager->flush();

            return $this->redirectToRoute('appartement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appartement/new.html.twig', [
            'appartement' => $appartement,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/voir-detail-appartement/{id}", name="appartement_show", methods={"GET"})
     * @param Appartement $appartement
     * @return Response
     */
    public function show(Appartement $appartement): Response
    {
//        dd($appartement->getAccessoires()[0]);
        return $this->render('appartement/show.html.twig', [
            'appartement' => $appartement,
        ]);
    }

    /**
     * @Route("/modifier-appartement/{id}/edit", name="appartement_edit", methods={"GET", "POST"})
     * @param Request $request
     * @param Appartement $appartement
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function edit(Request $request, Appartement $appartement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AppartementType::class, $appartement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('appartement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appartement/edit.html.twig', [
            'appartement' => $appartement,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/supprimer-appartement/{id}", name="appartement_delete", methods={"POST"})
     * @param Request $request
     * @param Appartement $appartement
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(Request $request, Appartement $appartement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$appartement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($appartement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('appartement_index', [], Response::HTTP_SEE_OTHER);
    }
}
