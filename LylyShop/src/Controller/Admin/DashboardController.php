<?php

namespace App\Controller\Admin;

// ===========================================================================
//                             DEBUT BLOG
// ===========================================================================
use App\Entity\Article;
use App\Entity\Category;
// ===========================================================================
//                             FIN BLOG
// ===========================================================================


use App\Entity\Product;
use App\Entity\Categories;
use App\Entity\Carrier;
use App\Entity\Order;
use App\Entity\Cart;
use App\Entity\Contact;
use App\Entity\User;
use App\Entity\HomeSlider;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);
        return $this->redirect($routeBuilder->setController(OrderCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('ECommerceSymfony');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');
        yield MenuItem::linkToCrud('Produits', 'fas fa-shopping-cart', Product::class);
        yield MenuItem::linkToCrud('Commande', 'fas fa-shopping-bag', Order::class);
        yield MenuItem::linkToCrud('Panier', 'fas fa-boxes', Cart::class);
        yield MenuItem::linkToCrud('Catégories Produits', 'fas fa-list', Categories::class);
        yield MenuItem::linkToCrud('Transporteur', 'fas fa-truck', Carrier::class);
        yield MenuItem::linkToCrud('AccueilSlider', 'fas fa-image', HomeSlider::class);
        yield MenuItem::linkToCrud('Contact', 'fas fa-envelope', Contact::class);
        yield MenuItem::linkToCrud('Articles Blog', 'fas fa-newspaper', Article::class);
        yield MenuItem::linkToCrud('Categories Blog', 'fas fa-list', Category::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);

    }
}
