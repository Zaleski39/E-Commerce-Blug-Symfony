<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }


    public function createEntity(string $entityFqcn)
    {
        $product = new Product();
        $product->setIsSpecialOffer(true);

        return $product;
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
                ImageField::new('image', 'Photo principale')
                    ->setBasePath('/assets/uploads/products/')
                    ->setUploadDir('/public/assets/uploads/products/')
                    ->setUploadedFileNamePattern('[randomhash].[extension]')
                    ->setRequired(false),
                TextField::new('name', 'Nom'),
                SlugField::new('slus')->setTargetFieldName('name'),
                AssociationField::new('category', 'Categorie')->onlyOnForms(),
                ArrayField::new('category', 'Categorie')->onlyOnIndex(),
                MoneyField::new('price', 'Prix')->setCurrency('EUR'),
                IntegerField::new('quantity', 'Quantité'),
                BooleanField::new('isBestSeller', 'Meilleurs Ventes'),
                BooleanField::new('isNewArrival', 'Nouvelle Arrivée'),
                IntegerField::new('reduction', 'Réduction en %'),
                TextField::new('tags', ('Mots clés')),
                TextEditorField::new('description', 'Description')->formatValue(function ($value) { return $value; }),
                TextEditorField::new('moreInformation', 'Plus d\'information')->formatValue(function ($value) { return $value; }),
                IdField::new('id')->hideOnForm(),
                // BooleanField::new('isFeatured', 'Populaire'),
                // BooleanField::new('isSpecialOffer', 'Offre spéciale'),
                                    
                                ];
    }
    
}
