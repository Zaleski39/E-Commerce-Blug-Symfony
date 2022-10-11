<?php

namespace App\Controller\Account;

use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Repository\AddressRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/account")
*/

class AccountController extends AbstractController
{
    /**
     * @Route("/", name="account")
     */
    public function index(OrderRepository $repoOrder, AddressRepository $addressRepository): Response
    {
        $orders = $repoOrder->findBy(
            ['isPaid' => true, 'user' => $this->getUser() ], 
            ['id' => 'DESC']
        );

        return $this->render('/account/index.html.twig',[
            'orders' => $orders,
            'addresses' => $addressRepository->findBy([
                'user' => $this->getUser()
            ]),
        ]
    
    );
    }

    /**
     * @Route("/order/{id}", name="account_order_details")
     */
    public function indeshowx(Order $order): Response
    {
        if(!$order || $order->getUser() !== $this->getUser()){
            return $this->redirectToRoute("home");
        }

        if(!$order->getIsPaid()){
            return $this->redirectToRoute("account");
        }
        return $this->render('account/detail_order.html.twig',[
            'order' => $order,
        ]);
    }
}
