<?php

namespace App\Controller;

use App\Entity\Session;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    /**
     * @Route("/session", name="session_index")
     * 
     * @param SessionRepository $repo
     * @return Response
     */
    public function index(SessionRepository $repo)
    {
        return $this->render('session/index.html.twig', [
            'sessions' => $repo->findAll()
        ]);
    }

    /**
     * Permet de supprimer un annonce
     * 
     * @Route("/session/{slug}/delete", name="session_delete")
     *
     * @param Session $session
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function delete(Session $session, EntityManagerInterface $em)
    {
        if (count($session->getClassrooms()) > 0) {
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer cette session, elle possède déjà des classes ! "
            );
        } else {
            $em->remove($session);
            $em->flush();

            $this->addFlash(
                'success',
                "La session <strong>{$session->getName()}</strong> a bien été supprimé"
            );
        }
        return $this->redirectToRoute("session_index");
    }

    /**
     * Permet d'éditer une session
     * 
     * @Route("/session/{slug}/edit", name="session_edit")
     *
     * @param Session $session
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function edit(Session $session, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session->updateSlug();
            $em->persist($session);
            $em->flush();

            $this->addFlash(
                'success',
                "La session <strong> {$session->getName()}</strong> a bien été modifiée !"
            );

            return $this->redirectToRoute("session_index");
        }

        return $this->render('session/edit.html.twig', [
            'session' => $session,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de créer une nouvelle session
     *
     * @Route("/session/new", name="session_create")
     * 
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return void
     */
    public function new(Request $request, EntityManagerInterface $em)
    {

        $session = new Session();

        $form = $this->createForm(SessionType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session->initializeSlug();
            $em->persist($session);
            $em->flush();

            $this->addFlash(
                'success',
                "La session <strong> {$session->getName()}</strong> a bien été enregistrer !"
            );

            return $this->redirectToRoute("session_index");
        }

        return $this->render('session/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
