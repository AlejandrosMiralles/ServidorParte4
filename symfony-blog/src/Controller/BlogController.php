<?php

namespace App\Controller;

use App\Entity\Post;

use App\Form\PostFormType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Persistence\ManagerRegistry ;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends AbstractController
{

    #[Route('/blog', name: 'blog')]
    public function blog(): Response{
        return $this->render('blog/index.html.twig', []);
    }

    #[Route('/blog/singlePost', name: 'singlePost')]
    public function singlePost(): Response{
        return $this->render('blog/singlePost.html.twig', []);
    }

    /**
     * @Route("/blog/new", name="new_post")
     */
    public function newPost(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger): Response{
        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();   
            $post->setSlug($slugger->slug($post->getTitle()));
            $post->setPostUser($this->getUser());
            $post->setNumLikes(0);
            $post->setNumComments(0);
            $entityManager = $doctrine->getManager();    
            $entityManager->persist($post);
            $entityManager->flush();
            return $this->render('blog/new_post.html.twig', array(
                'form' => $form->createView()    
            ));
        }
        return $this->render('blog/new_post.html.twig', array(
            'form' => $form->createView()    
        ));
    }

}
