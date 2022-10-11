<?php

namespace App\Controller\Admin;



use App\Entity\Article;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }
    
    public function configureCrud(Crud $crud): Crud
    {
    return $crud
    ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            TextEditorField::new('description', 'Desciption (255 cartères maxi)')->formatValue(function ($value) { return $value; }),
            TextEditorField::new('content')
                ->formatValue(function ($value) { return $value; })
                ->setFormType(CKEditorType::class),
            ImageField::new('image')->setUploadDir("public/assets/uploads/blog")->setBasePath("/assets/uploads/blog")->setRequired(false),
            AssociationField::new('categoryBlog')->onlyOnForms(),
            ArrayField::new('categoryBlog', 'categoryBlog')->onlyOnIndex(),
        ];
    }
    
}
