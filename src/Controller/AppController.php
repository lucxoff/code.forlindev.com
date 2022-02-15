<?php

namespace App\Controller;

use App\Entity\Code;
use App\Repository\CodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute('app_newcode');
    }

    /**
     * @Route("/new", name="app_newcode")
     */
    public function newCode(): Response
    {
        return $this->render('app/new.html.twig');
    }

    /**
     * @Route("/{id}", name="app_code_show")
     */
    public function showCode(CodeRepository $codeRepository, Request $request): Response
    {
        // Get the code from the database with the id
        $code = $codeRepository->findOneBy(['id' => $request->get('id')]);
        // If the code is not found, redirect to the homepage
        if (!$code) {
            return $this->redirectToRoute('app_homepage');
        }
        $content = $code->getContent();
        $content = stripslashes($content);

        return $this->render('app/show.html.twig', [
            'code' => $content,
            'lines' => $code->getLine(),
        ]);
    }
}
