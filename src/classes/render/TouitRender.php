<?php

namespace iutnc\touiteur\render;
use iutnc\touiteur\touit\Touit;
require_once'vendor/autoload.php';

class TouitRender {
    /**
     * @var Touit $touit : Touit à afficher
     */
    private Touit $touit;

    /**
     * @param Touit $t : Touit à afficher
     */
    public function __construct(Touit $t){
        $this->touit = $t;
    }

    /**
     * Methode pour afficher un touit
     *
     * @return string : Renvoie le touit sous forme de HTML
     */
    public function render(): string {
        $html = "<p>{$this->touit->user->nom}</p>";
        $html .= "<p>@"."{$this->touit->user->pseudo}</p>";
        $html .= "<p>{$this->touit->date}</p>";
        $html .= "<p>{$this->touit->texte}</p>";
        $html .= "<p>{$this->touit->note}</p>";
        return $html;
    }
}