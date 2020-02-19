<?php

namespace App\Controller;

use App\Entity\Intern;
use App\Repository\InternRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InternController extends AbstractController
{
    /**
     * Affiche la liste des stagiaire
     *
     *@Route("/intern", name="intern_index")
     *
     * @param InternRepository $repo
     * @return Response
     */
    public function index(InternRepository $repo)
    {
        $interns = $repo->findAll();

        return $this->render('intern/index.html.twig', [
            "interns" => $interns
        ]);
    }

    /**
     * Permet de supprimer un stagiaire
     * 
     * @Route("/intern/{slug}/delete", name="intern_delete")
     *
     * @param Intern $intern
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function delete(Intern $intern, EntityManagerInterface $em)
    {
        $em->remove($intern);
        $em->flush();

        $this->addFlash(
            'danger',
            "Le stagiaire <strong>{$intern->getFullName()}</strong> a bien été supprimé"
        );

        return $this->redirectToRoute("intern_index");
    }
}
