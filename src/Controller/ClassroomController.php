<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Form\ClassroomType;
use App\Repository\ClassroomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ClassroomController extends AbstractController
{
    /**
     * Affiche la liste des classes
     * 
     * @Route("/classroom", name="classroom_index")
     * 
     * @param ClassroomRepository $repo
     * @return Response
     */
    public function index(ClassroomRepository $repo)
    {
        return $this->render('classroom/index.html.twig', [
            'classRooms' => $repo->findAll()
        ]);
    }

    /**
     * Permet de supprimer une classe
     * 
     * @Route("/classroom/{id}/delete", name="classroom_delete")
     *
     * @param Classroom $classroom
     * @param EntityManagerInterface $em
     * @return Responsive
     */
    public function delete(Classroom $classroom, EntityManagerInterface $em)
    {

        if (count($classroom->getInterns()) > 0) {
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer cette classe, elle possède déjà des stagiares ! "
            );
        } else {
            $em->remove($classroom);
            $em->flush();

            $this->addFlash(
                'success',
                "La session <strong>{$classroom->getName()}</strong> a bien été supprimé"
            );
        }
        return $this->redirectToRoute("classroom_index");
    }

    /**
     * Permet de créer une nouvelle classe
     * 
     * @Route("/classroom/new", name="classroom_new")
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Reponse
     */
    public function new(Request $request, EntityManagerInterface $em)
    {

        $classroom = new Classroom();

        $form = $this->createForm(ClassroomType::class, $classroom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $classroom->initializeSlug();
            $em->persist($classroom);
            $em->flush();

            $this->addFlash(
                'success',
                "La classe <strong> {$classroom->getName()}</strong> a bien été enregistrer !"
            );

            return $this->redirectToRoute("classroom_index");
        }


        return $this->render('classroom/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
