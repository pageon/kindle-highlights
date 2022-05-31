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
                'label' => 'Select your <code>My Clippings.txt</code> file',
                'label_html' => true,
                'help' => '<p>After connecting your kindle to your computer you can find the file on your kindle at <code>documents/My Clippings.txt</code>.</p><p>I have only tested this on the paperwhite. Should you have any issues feel free to contact me with your <code>My Clippings.txt</code> file at <a href="mailto:info@pageon.be">info@pageon.be</a></p><p class="alert alert-warning mt-3">This file will not be stored permanently and will only be used during this visit</p>',
                'help_html' => true,
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
