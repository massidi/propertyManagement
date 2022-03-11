<?php

namespace App\Controller;

use App\Entity\Appartement;
use App\Entity\Image;
use App\Entity\Society;
use App\Form\AppartementType;
use App\Repository\AppartementRepository;
use App\Service\SearcheAppartement;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/appartement")
 */
class AppartementController extends AbstractController
{
    /**
     * @var FlashyNotifier
     */
    private $notifier;

    public function __construct(FlashyNotifier $notifier)
    {
        $this->notifier = $notifier;
    }


    /**
     * @Route("/mes-appartement", name="mes_appartement", methods={"GET"})
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
     * @Route("/filer-appartement", name="filter_appartement", methods={"GET"})
     * @param AppartementRepository $appartementRepository
     * @return Response
     */
    public function filter(AppartementRepository $appartementRepository,Request  $request): Response
    {

        $appartements=0;

        if ($request->isMethod('GET'))
        {
            $checkIn= new DateTime($request->query->get('checkIn'));
            $checkOut= new DateTime($request->query->get('checkOut'));

            $appartements = $appartementRepository->getAvailableRooms($checkIn->format('Y-m-d H:i:s'),$checkOut->format('Y-m-d H:i:s'));


        }



        return $this->render('appartement/filter.html.twig', [
            'appartements' => $appartements,
        ]);
    }




    /**
     * @Route("/liste-appartement", name="appartement_index", methods={"GET", "POST"})
     * @param AppartementRepository $appartementRepository
     * @return Response
     */
    public function Appartement(AppartementRepository $appartementRepository): Response
    {

        $appartements = $appartementRepository->findAll();


        return $this->render('appartement/filter.html.twig', [
            'appartements' => $appartements,
        ]);
    }

    /**
     * @Route("/ajouter-nouvel-appartement", name="appartement_new", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $entityManager, FlashyNotifier $notifier): Response
    {

        $appartement = new Appartement();
        $form = $this->createForm(AppartementType::class, $appartement);
        $form->handleRequest($request);


        //get the current user
        $user = $this->getUser();


        //find the society related to the current user
        $societe = $entityManager->getRepository(Society::class)->find($user);

        //if society doesn't existe redirect to the creation of a new socierty

        if (empty($societe)) {
            $notifier->warning("veillez commencer par créeer votre societé");
            return $this->redirectToRoute('society_new');
        }


        //assign the society into apartment

        $appartement->setSociety($societe);


        if ($form->isSubmitted() && $form->isValid())

            {


            if (empty($appartement->getCategory() && $appartement->getAccessoires())) {
                $notifier->error("Commencer par créer les accessoires et les categories");

                return $this->redirectToRoute('parametre');

            }
            // On récupère les images transmises
            $images = $form->get('image')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Image();
                $img->setNom($fichier);
                $appartement->addImage($img);
            }


            $entityManager->persist($appartement);
            $entityManager->flush();
            $this->notifier->success('Merci vous venez d\'ajouter une nouvelle habitation');


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

            // On récupère les images transmises
            $images = $form->get('image')->getData();

            // On boucle sur les images
            foreach ($images as $image) {
                // On génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                // On crée l'image dans la base de données
                $img = new Image();
                $img->setNom($fichier);
                $appartement->addImage($img);
            }


            $entityManager->flush();

            return $this->redirectToRoute('appartement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appartement/edit.html.twig', [
            'appartement' => $appartement,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="appartement_delete", methods={"POST","GET"})
     * @param Request $request
     * @param Appartement $appartement
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(Request $request, Appartement $appartement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $appartement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($appartement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mes_appartement', [], Response::HTTP_SEE_OTHER);
    }
}
