<?php

namespace App\Entity;


class EmailModel
{    
    private $title;
   
    private $subject;
   
    private $content;

    private $total;

    private $adresse;

    private $photo;

    private $designation;

    private $prixproduit;

    private $quantite;

    private $fraislivraison;

    private $panier;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(string $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getPrixproduit(): ?string
    {
        return $this->prixproduit;
    }

    public function setPrixproduit(string $prixproduit): self
    {
        $this->prixproduit = $prixproduit;

        return $this;
    }

    public function getQuantite(): ?string
    {
        return $this->quantite;
    }

    public function setQuantite(string $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getFraislivraison(): ?string
    {
        return $this->fraislivraison;
    }

    public function setFraislivraison(string $fraislivraison): self
    {
        $this->fraislivraison = $fraislivraison;

        return $this;
    }

    

    public function getPanier(): ?array
    {
        return $this->panier;
    }

    public function setPanier(array $panier): self
    {
        $this->panier = $panier;

        return $this;
    }





}
