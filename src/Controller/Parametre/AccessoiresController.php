<?php

namespace App\Controller\Parametre;


use App\Entity\Accessoires;
use App\Form\AccessoiresType;
use App\Repository\AccessoiresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("Admin/accessoires")
 */
class AccessoiresController extends AbstractController
{
    /**
     * @Route("/", name="accessoires_index", methods={"GET"})
     */
    public function index(AccessoiresRepository $accessoiresRepository): Response
    {
        return $this->render('parametre/accessoires/index.html.twig', [
            'accessoires' => $accessoiresRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="accessoires_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $accessoire = new Accessoires();
        $form = $this->createForm(AccessoiresType::class, $accessoire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($accessoire);
            $entityManager->flush();

            return $this->redirectToRoute('accessoires_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('parametre/accessoires/new.html.twig', [
            'accessoire' => $accessoire,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="accessoires_show", methods={"GET"})
     */
    public function show(Accessoires $accessoire): Response
    {
        return $this->render('parametre/accessoires/show.html.twig', [
            'accessoire' => $accessoire,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="accessoires_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Accessoires $accessoire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AccessoiresType::class, $accessoire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('accessoires_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('parametre/accessoires/edit.html.twig', [
            'accessoire' => $accessoire,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="accessoires_delete", methods={"POST"})
     */
    public function delete(Request $request, Accessoires $accessoire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$accessoire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($accessoire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('accessoires_index', [], Response::HTTP_SEE_OTHER);
    }
}
