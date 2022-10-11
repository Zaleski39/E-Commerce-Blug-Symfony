<?php

// Panier

namespace App\Services;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartServices{

    private $session;
    private $repoProduct;
    private $tva = 0.2;

        public function __construct(SessionInterface $session, ProductRepository $repoProduct) {
            $this->session = $session;
            $this->repoProduct = $repoProduct;
        }

    // Ajouter un produit au panier

        public function addToCart($id) {
            // On récupère la panier
            $cart = $this->getCart();
            if (isset($cart[$id])) {
                // Produit déjà dans le panier
                    $cart[$id]++;
            } else {
                // le produit n'est pas dans le panier
                    $cart[$id] = 1;
            }
            // Mise à jour du panier
            $this->updateCart($cart);
        }

    // Supprimer un produit du panier

        public function deleteFromCart($id) {
            // On récupère la panier
            $cart = $this->getCart();
            // Produit déjà dans le panier :
            if (isset($cart[$id])) {
                // Produit déjà dans le panier avec quantité > 1 :
                if ($cart[$id] > 1) {
                        $cart[$id]--;
                } else {
                    // Produit déjà dans le panier avec quantité = 1 :
                    unset($cart[$id]);
                }
                // Mise à jour du panier
                $this->updateCart($cart);
            }
        }

    // Supprimer tous les produits du panier (plusieurs quantités)
        public function deleteAllToCart($id) {
            // On récupère la panier
            $cart = $this->getCart();

            // Produit déjà dans le panier :
            if (isset($cart[$id])) {
               unset($cart[$id]);
               // Mise à jour du panier
                $this->updateCart($cart);
            }
        }

    // Supprimer le panier
        public function deleteCart() {
            // On vide la panier
            $this->updateCart([]);
        }

    // Mise à jour du panier
        public function updateCart($cart) {
            $this->session->set('cart', $cart);
            // Permet de récupérer les infos à jour du panier et envoyer les infos dans la session
            $this->session->set('cartData', $this->getFullCart());
        }

    // Retourner le contenu du panier
        public function getCart() {
            return $this->session->get('cart', []);
        }

    // Récupérer le panier complet
        public function getFullCart() {

            $cart = $this->getCart();
            $fullCart = [];
            $quantity_cart = 0;
            $subTotal = 0;

            foreach ($cart as $id => $quantity) {

                $product = $this->repoProduct->find($id);
                // On test si on récupère le produit
                if ($product) {
                    // Produit récupéré avec succès
                        // Permet de vérifier si la quantité en stock et supérieur à la quantité demander.
                        // Si non, nous modifions la quantité de l'utilisateur par la quantité en stock
                        if ($quantity > $product->getQuantity()) {
                            $quantity = $product->getQuantity();
                            $cart[$id] = $quantity;
                            $this->updateCart($cart);
                        }

                    $fullCart['products'] []=
                    [
                        "quantity" => $quantity,
                        "product" => $product
                    ];
                    // Incrémenter la quantité dans le panier
                    $quantity_cart += $quantity;
                    // Incrémenter le prix des produit
                    $subTotal += $quantity * ($product->getPrice()/100);
                }else {
                     // Produit non récupéré, id Incorrect
                     $this->deleteFromCart($id);
                }
            }
            $fullCart['data'] = [
                "quantity_cart" => $quantity_cart,
                "subTotalHT" => round($subTotal, 2),
                "taxe" => round(($subTotal*$this->tva), 2),
                "subTotalTTC" => round(($subTotal + ($subTotal*$this->tva)), 2)
            ];

            return $fullCart;
        }
}