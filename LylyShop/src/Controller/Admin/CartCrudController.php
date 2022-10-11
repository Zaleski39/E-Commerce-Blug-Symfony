<?php

namespace App\Controller\Admin;

use App\Entity\Cart;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CartCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Cart::class;
    }

    public function configureCrud(Crud $crud): Crud {
        return $crud->setDefaultSort(['id' => 'DESC']);
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('user.FullName', 'Client'),
            TextField::new('carrierName','Nom Transporteur'),
            MoneyField::new('CarrierPrice', 'Coût Transport')->setCurrency('EUR'),
            MoneyField::new('subTotalHT', 'Total HT')->setCurrency('EUR'),
            MoneyField::new('taxe', 'TVA')->setCurrency('EUR'),
            MoneyField::new('subTotalTTC', 'Total TTC')->setCurrency('EUR'),
            // BooleanField::new('isPaid', 'Est Payé ?'),
        ];
    }
   
}
