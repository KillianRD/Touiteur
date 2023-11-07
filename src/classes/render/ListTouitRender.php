<?php

namespace iutnc\touiteur\render;
require_once'vendor/autoload.php';
use iutnc\touiteur\lists\ListTouit;
use iutnc\touiteur\touit\User;

class ListTouitRender {

    private ListTouit $listTouits;

    /**
     * @param ListTouit $listTouits
     */
    public function __construct(ListTouit $listTouits){
        $this->listTouits = $listTouits;
    }

    public function render_home() : string { // TODO Requete SQL pour recuperer l'ensemble des touits de la BD
        $html = "<h3>Accueil</h3><br>";
        $html .= "<ul>";
        foreach($this->listTouits->touits as $touit) {
            $touitrender = new TouitRender($touit);
            $html .= "<li>{$touitrender->render()}</li>";
        }
        $html .= "<br></ul>";
        return $html;
    }

    public function render_user(User $user) :string {
        $html = "<h3>{$user->pseudo}</h3><br>";
        $html .= "<ul>";
        foreach($user->listTouits->touits as $touit) {
            $touitrender = new TouitRender($touit);
            $html .= "<li>{$touitrender->render()}</li>";
        }
        $html .= "<br></ul>";
        return $html;
    }



}