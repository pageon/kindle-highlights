<?php

namespace App\Controller;

use App\Domain\Clippings\Parser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class ClippingsController extends AbstractController
{
    #[Route('/', name: 'clippings_index')]
    public function index(Request $request): Response
    {
        $form = $this->createFormBuilder()->add(
            'clipping',
            FileType::class,
            [
                'attr' => ['accept' => 'text/*', 'onchange' => 'form.submit()'],
                'constraints' => [new File(mimeTypes: ['text/*'])],
            ]
        )->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->render('clippings/index.html.twig', [
                'bookHighlights' => Parser::fromPath($form->getData()['clipping']->getPathname()),
            ]);
        }

        return $this->render('clippings/index.html.twig', ['form' => $form->createView()]);
    }
}
