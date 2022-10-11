<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Categories;
use App\Entity\SearchProduct;
use App\Form\SearchProductType;
use App\Repository\ProductRepository;
use App\Repository\HomeSliderRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

//==========================================================================
//                             DEBUT BLOG
// ===========================================================================
use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Entity\Category;
// ===========================================================================
//                             FIN BLOG
// ===========================================================================


class HomeController extends AbstractController
{   // ===========================================================================
    //                             DEBUT BLOG
    // ===========================================================================
        private $repoArticle;
        private $repoCategory;

        public function __construct(ArticleRepository $repoArticle, CategoryRepository $repoCategory  )
        {
            $this->repoArticle = $repoArticle;
            $this->repoCategory = $repoCategory;
        }

    // ===========================================================================
    //                             FIN BLOG
    // ===========================================================================


    /**
     * @Route("/", name="home")
     */
    public function index(ProductRepository $repoProduct, HomeSliderRepository $repoHomeSlider): Response
    {
        $homeSlider = $repoHomeSlider->findBy(['isDisplayed'=>true]);
        $products = $repoProduct->findAll();
        $productsNewArrival = $repoProduct->findByIsNewArrival(1);
        $productsBestSeller = $repoProduct->findByIsBestSeller(1);
        $productsFeatured = $repoProduct->findByIsFeatured(1);
        $productsSpecialOffer = $repoProduct->findByIsSpecialOffer(1);

            // dd( $products, $productsBestSeller, $productsNewArrival, $productsFeatured, $productsSpecialOffer );

        return $this->render('home/index.html.twig', [
            'products' => $products,
            'productsNewArrival' => $productsNewArrival,
            'productsBestSeller' => $productsBestSeller, 
            'productsFeatured' => $productsFeatured,      
            'productsSpecialOffer' => $productsSpecialOffer,
            'homeSlider' => $homeSlider,
        ]);
    }

    /**
     * @Route("product/{slus}", name="product_details")
    */
    public function show(?Product $product): Response{
            if (!$product){
                return $this->redirectToRoute('home');
            }
            return $this->render("home/single_product.html.twig", [
                'product' => $product
            ]);
    }

    /**
     * @Route("/shop", name="shop")
     */
    public function shop(ProductRepository $repoProduct, Request $request): Response
    {
        $products = $repoProduct->findAll();

        $search = new SearchProduct();

        $form = $this->createForm(SearchProductType::class, $search);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $products = $repoProduct->findWithSearch($search);
        }

            // dd( $form );

        return $this->render('home/shop.html.twig', [
            'products' => $products,
            'search' => $form->createView(),

        ]);
    }



    // ===========================================================================
    //                             DEBUT BLOG
    // ===========================================================================


    /**
     * @Route("/blog", name="blog")
     */
    public function indexBlog(): Response
        {
            $articles = $this->repoArticle->findAll();
            $categories = $this->repoCategory->findAll();
            // dd($categories);
            return 
                $this->render("blog/index.html.twig", [
                'articles' => $articles,
                'categories' => $categories,
            ]);
        }

    /**
     * @Route("/show/{id}", name="show")
     */
    public function showBlog(Article $article): Response
    {
        if (!$article) {
            return $this->redirectToRoute('home');
        }
        // dd($article);
        return $this->render("Blog/show.html.twig", [
            'article' => $article,
        ]);
    }



    /**
     * @Route("/showArticle/{id}", name="showArticle")
     */
    public function showArticle(?Category $category): Response
    {
        $categories = $this->repoCategory->findAll();

        if ($category) {            
            $articles = $category->getArticles()->getValues();
        } else {
            return $this->redirectToRoute('home');
        };      
            // dd($articles);    
            return $this->render("blog/index.html.twig", [
                'articles' => $articles,
                'categories' => $categories,
            ]);
    }



    // ===========================================================================
    //                             FIN BLOG
    // ===========================================================================



}
