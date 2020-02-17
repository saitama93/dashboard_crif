<?php

namespace App\Controller;

use App\Repository\SessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends AbstractController
{
    /**
     * @Route("/session", name="session_index")
     */
    public function index(SessionRepository $repo)
    {
        return $this->render('session/index.html.twig', [
            'sessions' => $repo->findAll()
        ]);
    }
}
