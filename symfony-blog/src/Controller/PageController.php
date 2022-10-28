<?php

namespace App\Controller;

use App\Entity\Contact;

use App\Form\ContactFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Persistence\ManagerRegistry ;

use Symfony\Component\HttpFoundation\Request;

class PageController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('page/index.html.twig', []);
    }

    /**
    * @Route("/about", name="about")
    */
    public function about(): Response
    {
        return $this->render('page/about.html.twig', []);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(ManagerRegistry $doctrine, Request $request): Response{
        $contact = new Contact();

        $formulario=  $this->createForm(ContactFormType::class, $contact);
                    
        $formulario->handleRequest($request);

        if($formulario->isSubmitted() && $formulario->isValid()){
            $contact = $formulario->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($contact);
            
            $entityManager->flush();
            return $this->redirectToRoute('thank_you', []);
        }

        return $this->render('page/contact.html.twig', array('form' => $formulario->createView()));
    }

    #[Route('/thankyou', name:'thank_you')]
    public function thankYou(): Response {
        return $this->render('page/thankyou.html.twig', []);
    }

}
