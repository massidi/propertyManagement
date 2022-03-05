<?php

namespace App\Controller\Parametre;


use App\Entity\Society;
use App\Entity\User;
use App\Form\SocietyType;
use App\Repository\SocietyRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("Admin/society")
 */
class SocietyController extends AbstractController
{
    /**
     * @Route("/", name="society_index", methods={"GET"})
     */
    public function index(SocietyRepository $societyRepository): Response
    {
        if (empty($societyRepository->findAll()))
        {
            return $this->redirectToRoute('society_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('parametre/society/index.html.twig', [
            'societies' => $societyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="society_new", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function new(Request $request, FileUploader $fileUploader, EntityManagerInterface $entityManager): Response
    {
        $society = new Society();
       $user= $entityManager->getRepository(User::class)->find($this->getUser());
        $society->addUser($user);

        $form = $this->createForm(SocietyType::class, $society);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                $imageName = $fileUploader->upload($image);
                $society->setImage($imageName);
            }



            $entityManager->persist($society);
            $entityManager->flush();

            return $this->redirectToRoute('society_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('parametre/society/new.html.twig', [
            'society' => $society,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/voir_detaile_societé/{id}", name="society_show", methods={"GET"})
     */
    public function show(Society $society): Response
    {
        return $this->render('parametre/society/show.html.twig', [
            'society' => $society,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="society_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Society $society, EntityManagerInterface $entityManager,FileUploader  $uploader): Response
    {
        $form = $this->createForm(SocietyType::class, $society);

//        dd($form);
//        $images = $society->getImage()->getNom();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('image')->getData();
            if ($image) {
                $imageName = $uploader->upload($image);
                $society->setImage($imageName);

            }







            $entityManager->flush();

            return $this->redirectToRoute('society_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('parametre/society/edit.html.twig', [
            'society' => $society,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/society_delete/{id}", name="society_delete", methods={"POST"})
     */
    public function delete(Request $request, Society $society, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$society->getId(), $request->request->get('_token'))) {

            $entityManager->remove($society);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_logout', [], Response::HTTP_SEE_OTHER);
    }
}
