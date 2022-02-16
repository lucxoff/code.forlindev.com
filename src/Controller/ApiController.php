<?php

namespace App\Controller;

use App\Entity\Code;
use App\Repository\CodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/save", name="save_code")
     */
    public function index(Request $request, CodeRepository $codeRepository): JsonResponse
    {
        // Verify if the request is a POST request
        if ($request->getMethod() !== 'POST') {
            return new JsonResponse('Method not allowed', Response::HTTP_METHOD_NOT_ALLOWED);
        }

        // Get the code from the request
        $data = [];
        if ($contentRequest = $request->getContent()) {
            $data = json_decode($contentRequest, true);
        }
        $content = $data['code'];

        // Mysql real escape string
        $content = addslashes($content);

        // Verify if the code is not empty
        if (empty($content)) {
            return new JsonResponse('Code cannot be empty', Response::HTTP_BAD_REQUEST);
        }

        // Save the code in the database
        $code = new Code();
        $code->setContent($content);
        $code->setCreatedAt(new \DateTimeImmutable());
        $code->setLine(substr_count($content, "\n"));
        $codeRepository->save($code);

        // Return the code
        return new JsonResponse(['id' => $code->getId()], Response::HTTP_CREATED);

    }
}
