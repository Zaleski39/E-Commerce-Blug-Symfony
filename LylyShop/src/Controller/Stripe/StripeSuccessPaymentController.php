<?php

namespace App\Controller\Stripe;

use App\Entity\User;

use App\Entity\EmailModel;
use App\Entity\Order;
use App\Services\EmailSender;
use App\Services\CartServices;
use App\Services\StockManagerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeSuccessPaymentController extends AbstractController
{
    /**
     * @Route("/stripe-payment-success/{StripeCheckoutSessionId}", name="stripe_payment_success")
     */
    public function index(?Order $order, CartServices $cartServices, EntityManagerInterface $manager, StockManagerService $stockManager, EmailSender $emailsender ): Response
    {
        if (!$order || $order->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if (!$order->getIsPaid()) {

            // envoyé un mail pour le succès du paiment
                $user = (new User())
                        ->setEmail($order->getUser()->getEmail())
                        ->setFirstname($order->getUser()->getFirstname())
                        ->setLastname($order->getUser()->getLastname());
                // dd($user);

                $email = (new EmailModel())
                        ->setTitle($user->getFullName())
                        ->setSubject("Nouvel achat")                        
                        ->setTotal($order->getSubTotalTTC()/100)
                        ->setAdresse($order->getDeliveryAddress())
                        ->setPanier($cartServices->getFullCart())
                        ;

                $emailsender->sendEmailConfirmationPanierByMailJet($user,$email);

                // dd($cartServices->getFullCart());

            // commande payée
                $order->setIsPaid(true);
            // enlever les produits du stock
                $stockManager->deStock($order);
            // Supprimer le panier
            $cartServices->deleteCart();

                $manager->flush();

        }

        return $this->render('stripe/stripe_success_payment/index.html.twig', [
            'controller_name' => 'StripeSuccessPaymentController',
            'order' => $order
        ]);
    }
}
