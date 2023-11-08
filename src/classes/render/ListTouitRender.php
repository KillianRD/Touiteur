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

    /**
     * Methode pour afficher la liste des touits
     * @return array : liste des touits
     * @throws \iutnc\touiteur\exceptions\InvalideTouitException
     */
    public static function render_home() : array { // TODO Requete SQL pour recuperer l'ensemble des touits de la BD
        $connextion = ConnectionFactory::makeConnection();
        $requete = $connextion->prepare("SELECT texte, date, note, chemin, touite.id FROM touite NATURAL JOIN touite2image NATURAL JOIN image");
        $requete->execute();

        $touites = [];
        foreach ($requete->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $id = $row['id'];
            $texte = $row['texte'];
            $date = $row['date'];
            $note = $row['note'];
            $chemin = $row['chemin'];
            $pseudo = self::recherche_pseudo($id);

            $t = new Touit($texte, $pseudo, $date, $note, $chemin);

            array_push($touites, $t);
        }
        return $touites;
    }

    /**
     * Methode pour trouver le pseudo d'un user
     * @param int $id : id du touit
     * @return String : pseudo du user
     */
    private static function recherche_pseudo(int $id) : String {
        $connextion = ConnectionFactory::makeConnection();
        $requete= $connextion->prepare("SELECT pseudo FROM user NATURAL JOIN user2touite NATURAL JOIN touite WHERE touite.id = ?");
        $requete->bindParam(1, $id);
        $requete->execute();

        return $requete->fetch(\PDO::FETCH_ASSOC)['pseudo'];
    }

    /**
     * Methode pour afficher tout les touites d'un user
     * @param User $user : user
     * @return string : tous les touits d'un user
     */
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