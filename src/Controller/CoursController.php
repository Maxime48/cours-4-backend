<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CoursRepository;
use App\Entity\Cours;

class CoursController extends AbstractController
{
    #[Route('/cours', name: 'app_cours')]
    public function index(CoursRepository $coursRepository): Response
    {
        $cours = $coursRepository->findAll();

        return $this->render('cours/index.html.twig', [
            'cours' => $cours,
        ]);
    }

    #[Route('/cours/add', name: 'app_add_cours', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $cours = new Cours();
        $cours->setNom($request->request->get('nom'));
        $cours->setProfesseur($request->request->get('professeur'));
        $cours->setDuree($request->request->get('duree'));

        $entityManager->persist($cours);
        $entityManager->flush();

        return $this->redirectToRoute('app_cours');
    }

    #[Route('/cours/delete/{id}', name: 'app_delete_cours', methods: ['GET'])]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $cours = $entityManager->getRepository(Cours::class)->find($id);

        if ($cours) {
            $entityManager->remove($cours);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cours');
    }
}
