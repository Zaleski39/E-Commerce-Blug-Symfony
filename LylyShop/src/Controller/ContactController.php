<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Entity\EmailModel;
use App\Services\EmailSender;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/contact")
 */
class ContactController extends AbstractController
{  
    /**
     * @Route("/", name="contact_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, EmailSender $emailsender): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();

            // Envoi d'email

            $user = (new User())
                    ->setEmail('codeuralexis@gmail.com')
                    ->setFirstname('Lyly')
                    ->setLastname('Shop');

            $email = (new EmailModel())
                    ->setTitle("Bonjour ".$user->getFullName())
                    ->setSubject("Nouveau contact depuis votre site Web")
                    ->setContent("<br>De : ".$contact->getEmail()
                                ."<br> Nom : ".$contact->getName()
                                ."<br> Sujet : ".$contact->getSubject()
                                ."<br><br>".$contact->getContent());

            $emailsender->sendEmailNotificationByMailJet($user,$email);
            // dd($emailsender->sendEmailNotificationByMailJet($user,$email));

            $contact = new Contact();
            $form = $this->createForm(ContactType::class, $contact);
            $this->addFlash('contact_success', 'Votre message a bien été envoyé. Nous reviendrons vers vous rapidement. Merci pour votre confiance');
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('contact_error', 'Le formulaire contient des erreurs. Veuillez corriger et réessayer');
        }

        return $this->renderForm('contact/new.html.twig', [
            'contact' => $contact,
            'form' => $form,
        ]);
    }

}
