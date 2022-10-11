<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Product;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DataLoaderController extends AbstractController
{
    /**
     * @Route("/data", name="data_loader")
     */
    public function index(EntityManagerInterface $manager, UserRepository $repoUser): Response
    {
        // Récupérer les produits depuis un fichier product.json (export de la base de données)
                $file_product = dirname(dirname(__DIR__))."\product.json";
                $data_product = json_decode(file_get_contents($file_product))[2]->data;        

        // Récupérer les catégories depuis un fichier categories.json (export de la base de données)
                $file_categories = dirname(dirname(__DIR__))."\categories.json";
                $data_categories = json_decode(file_get_contents($file_categories))[2]->data;        
                dd($data_categories);

                $categories=[];

        // Boucler dur l'ensembles des categories
                foreach ($data_categories as $data_category) {
                   $category = new Categories();
                   $category->setName($data_category->name)
                            ->setImage($data_category->image);
                        // $manager->persist($category);
                        $categories[]= $category;
                }

    // Boucler dur l'ensembles des produits
                foreach ($data_product as $data_produc) {
                $product = new Product();
                $product ->setName($data_produc->name)
                            ->setDescription($data_produc->description)
                            ->setPrice($data_produc->price)
                            ->setIsBestSeller($data_produc->is_best_seller)
                            ->setIsNewArrival($data_produc->is_new_arrival)
                            ->setIsFeatured($data_produc->is_featured)
                            ->setIsSpecialOffer($data_produc->is_special_offer)
                            ->setImage($data_produc->image)
                            ->setQuantity($data_produc->quantity)
                            ->setTags($data_produc->tags)
                            ->setSlus($data_produc->slus)
                            ->setCreatedAt(new \DateTime());            
                    // $manager->persist($product);
                        $products[]= $product;
                }






        // ====================================================================================================
        //    Pour passer un utilisateur en mode admin
                // Commenter les lignes 37 & 56   (empêche de pousser les données des tables)
                // Décommenter les lignes 75 à 77
                // Mettre le numero d'id de l'utilisateur ligne 74
                // aller sur l'url du site et mettre /data
                // ATTENTION, ne pas oublier de commenter le flush à la fin (ligne77) !!!!!!!!!!!!
        // ====================================================================================================


        $user = $repoUser->find(1);
        $user->setRoles(['ROLE_ADMIN']);                    
        $manager->flush();

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/DataLoaderController.php',
        ]);
    }
}
