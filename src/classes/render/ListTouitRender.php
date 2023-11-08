<?php

namespace iutnc\touiteur\render;
require_once'vendor/autoload.php';

use iutn\touiter\db\ConnectionFactory;
use iutnc\touiteur\lists\ListTouit;
use iutnc\touiteur\touit\Touit;
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
        $connextion = ConnectionFactory::makeConnection();
        $requete = $connextion->prepare("SELECT texte, date, note, description, chemin FROM touite NATURAL JOIN touite2image NATURAL JOIN image");
        $requete->execute();
        $touites = [];
        foreach ($requete->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $texte = $row['texte'];
            $date = $row['date'];
            $note = $row['note'];
            $description = $row['description'];
            $chemin = $row['chemin'];
            $t = new Touit($texte, $date, $note, $description, $chemin);
            array_push($touites, $t);
        }
        $html = "<h3>Accueil</h3><br>";
        $html .= "<ul>";
        foreach($touites as $touit) {
            $touitrender = new TouitRender($touit);
            $html .= "<li>{$touitrender->render()}</li>";
        }
        $html .= "<br></ul>";
        return $html;

        /**
         *
         *
         * $touites = [];
         * foreach ($requete->fetchAll(\PDO::FETCH_ASSOC) as $row) {
         * $texte = $row['texte'];
         * $date = $row['date'];
         * $note = $row['note'];
         * $description = $row['description'];
         * $chemin = $row['chemin'];
         * $t = new Touit($texte, $date, $note, $description, $chemin);
         * array_push($touites, $t);
         * }
         *
         * SELECT pseudo FROM user NATURAL JOIN user2touite NATURAL JOIN touite WHERE touite.id = ?  ;
         */
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