<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{

    #[Route('/blog', name: 'blog')]
    public function blog(): Response
    {
        return $this->render('blog/index.html.twig', []);
    }

    #[Route('/blog/singlePost', name: 'singlePost')]
    public function singlePost(): Response
    {
        return $this->render('blog/singlePost.html.twig', []);
    }
}
