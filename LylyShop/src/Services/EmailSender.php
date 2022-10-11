<?php
namespace App\Services;

use Mailjet\Client;
use App\Entity\User;
use Mailjet\Resources;
use App\Entity\EmailModel;

class EmailSender{


    public function sendEmailNotificationByMailJet(User $user, EmailModel $email){

    

        $mj = new Client($_ENV["MJ_APIKEY_PUBLIC"] , $_ENV["MJ_APIKEY_PRIVATE"],true,['version' => 'v3.1']);

        $body = [
                'Messages' => [
                [
                    'From' => [
                    'Email' => "codeuralexis@gmail.com",
                    'Name' => "Lyly Shop"
                    ],
                    'To' => [
                    [
                        'Email' => $user->getEmail(),
                        'Name' => $user->getFullName()
                    ]
                    ],
                    'TemplateID' => 3692832,
                    'TemplateLanguage' => true,
                    'Subject' => $email->getSubject(),
                    'Variables' => [
                        'title' => $email->getTitle(),
                        'content' => $email->getContent(),
                        
                    ]
                ]
                ]
        ];

        $response = $mj->post(Resources::$Email, ['body' => $body]);
    }


public function sendEmailConfirmationPanierByMailJet(User $user, EmailModel $email){
    $produits = null;
    $mailContent ='';
    $produits = $email->getPanier();
  
    // dd($produits);

    // dd($produits['products']); ==>> On doit se positionner ici pour boucler & on accède aux propriétés de l'objet depuis les getters de l'entité

    foreach($produits['products'] as $value){
        $mailContent .= "<li>"
                .$value["product"]->getName()
                ." <br> Quantité : "
                .$value["quantity"]."<br> Total TTC : "
                .($value["product"]->getPrice() * $value["quantity"]) / 100
                ."€ </li> <br>";
    }
        // dd($mailContent);
      

    $adresseCorrigee =  str_replace('[spr]', '<br>', $email->getAdresse());

    $mj = new Client($_ENV["MJ_APIKEY_PUBLIC"] , $_ENV["MJ_APIKEY_PRIVATE"],true,['version' => 'v3.1']);

    $body = [
            'Messages' => [
            [
                'From' => [
                'Email' => "codeuralexis@gmail.com",
                'Name' => "Lyly Shoop"
                ],
                'To' => [
                [
                    'Email' => $user->getEmail(),
                    'Name' => $user->getFullName()
                ]
                ],
                'TemplateID' => 3788629,
                'TemplateLanguage' => true,
                'Subject' => $email->getSubject(),
                'Variables' => [
                    'fullname' => $email->getTitle(),
                    'subTotalTTC' => $email->getTotal(),
                    'adresse' => $adresseCorrigee,
                    'panier' => "<ul>".$mailContent."</ul>"
                ]
            ]
            ]
    ];

    $response = $mj->post(Resources::$Email, ['body' => $body]);
}


}