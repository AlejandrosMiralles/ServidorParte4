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

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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
        
        //Reenviar al usuario a /login si no está conectado
        if (! $this->getUser()){
            return $this->redirectToRoute('app_login');
        }
        
        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        
                // Move the file to the directory where images are stored
                try {
                    
                    $file->move(
                        $this->getParameter('images_directory'), $newFilename
                    );
                    $filesystem = new Filesystem();
                    $filesystem->copy(
                            $this->getParameter('images_directory') . '/'. $newFilename, 
                            $this->getParameter('portfolio_directory') . '/'.  $newFilename, true);
                   
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
        
                // updates the 'file$filename' property to store the PDF file name
                // instead of its contents
                $post->setImage($newFilename);
            }
            
            $post = $form->getData();   
            $post->setSlug($slugger->slug($post->getTitle()));
            $post->setPostUser($this->getUser());
            $post->setNumLikes(0);
            $post->setNumComments(0);
            $entityManager = $doctrine->getManager();    
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('single_post', ["slug" => $post->getSlug()]);
        }
        return $this->render('blog/new_post.html.twig', array(
            'form' => $form->createView()    
        ));
    }

    /**
     * @Route("/single_post/{slug}", name="single_post")
     */
    public function post(ManagerRegistry $doctrine, $slug): Response
    {
        $repositorio = $doctrine->getRepository(Post::class);
        $post = $repositorio->findOneBy(["slug"=>$slug]);
        return $this->render('blog/singlePost.html.twig', [
            'post' => $post,
        ]);
    }


}
