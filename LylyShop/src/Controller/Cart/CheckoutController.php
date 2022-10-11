<?php

namespace App\Controller\Cart;

use App\Form\CheckoutType;
use App\Services\CartServices;
use App\Services\OrderServices;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CheckoutController extends AbstractController
{

    private $cartServices;
    private $session;

    public function __construct(CartServices $cartServices, SessionInterface $session) {
        $this->cartServices = $cartServices;
        $this->session = $session;
    }
    

    /**
     * @Route("/checkout", name="checkout")
     */
    public function index(Request $request): Response
    {
        // On récupère l'utilisateur connecté
        $user = $this->getUser();
        // On récupère le panier
        $cart = $this->cartServices->getFullCart();

        // On regarde si il y a quelquechose dans le panier, si non, on le redirige sur la page d'accueil
        if (!isset($cart['products'])) {
             return $this->redirectToRoute('home');
        }

        // On regarde l'utilisateur à des adresses enregistrées, si non on le redirige sur la page pour créer une adresse
        if (!$user->getAddresses()->getValues()) {
            // Afficher un message flash
            $this->addFlash('checkout_message', 'Merci d\'ajouter une adresse à votre compte pour commander.');
            // Rediriger sur la page nouvelle adresse
            return $this->redirectToRoute('address_new');
        }

        if ($this->session->get('checkout_data')) {
            return $this->redirectToRoute('checkout_confirm');
        }

        $form = $this->createForm(CheckoutType::class, null, ['user'=>$user]);

        return $this->render('checkout/index.html.twig', [
            'cart'=> $cart,
            'checkout' => $form->createView(),
        ]);
    }

    /**
     * @Route("/checkout/confimr", name="checkout_confirm")
     */
    public function confirm(Request $request, OrderServices $orderServices): Response{
            // On récupère l'utilisateur connecté
            $user = $this->getUser();
            // On récupère le panier
            $cart = $this->cartServices->getFullCart();

            // On regarde si il y a quelquechose dans le panier, si non, on le redirige sur la page d'accueil
            if (!isset($cart['products'])) {
                return $this->redirectToRoute('home');
            }

            // On regarde l'utilisateur à des adresses enregistrées, si non on le redirige sur la page pour créer une adresse
            if (!$user->getAddresses()->getValues()) {
                // Afficher un message flash
                $this->addFlash('checkout_message', 'Merci d\'ajouter une adresse à votre compte pour commander.');
                // Rediriger sur la page nouvelle adresse
                return $this->redirectToRoute('address_new');
            }

            $form = $this->createForm(CheckoutType::class, null, ['user'=>$user]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid() || $this->session->get('checkout_data')) {
                

                if ($this->session->get('checkout_data')){
                    $data = $this->session->get('checkout_data');
                } else {
                    $data = $form->getData();
                    $this->session->set('checkout_data',$data);
                }

                // Récupérer les données envoyées par le formulaire
                $address = $data['address'];
                $carrier = $data['carrier'];
                $information = $data['informations'];

                // Save Cart
                $cart['checkout'] = $data;
                $reference = $orderServices->saveCart($cart,$user);

                // Affichage des infos stockés dans la session
                // dd($this->session);
                // dd($_SESSION);


                return $this->render('checkout/confirm.html.twig', [
                    'cart'=> $cart,
                    'address'=> $address,
                    'carrier'=> $carrier,
                    'informations'=> $information,
                    'reference' => $reference,
                    'checkout' => $form->createView(),
                ]);
            }
            return $this->redirectToRoute('checkout');
    }

    /**
     * @Route("/checkout/edit", name="checkout_edit")
     */
    public function checkoutEdit():Response{
        $this->session->set('checkout_data',[]);
        return $this->redirectToRoute("checkout");
    }

}


