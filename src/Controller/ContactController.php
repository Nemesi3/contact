<?php

namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Contact;
use App\Form\ContactFormType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Twig\Environment;

class ContactController extends AbstractController
{
    /**
     * @Route("/show")
     */
    public function show(Environment $twig, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
       $contact = new Contact();

       $form = $this->createForm(ContactFormType::class, $contact);

       $form->handleRequest($request);



       if($form->isSubmitted() && $form->isValid()){

        $entityManager->persist($contact);
        $entityManager->flush();

        $this->addFlash('text', 'Köszönjük szépen a kérdésedet.
        Válaszunkkal hamarosan keresünk a megadott e-mail címen.');

        $this->addFlash('class', 'alert-success');

       }else{

        $this->addFlash('text', 'Hiba! Kérjük töltsd ki az
        összes mezőt!');

        $this->addFlash('class', 'alert-danger');
       }


       return new Response($twig->render('show.html.twig',[
        'contact_form' => $form->createView()
       ]));


    }
}