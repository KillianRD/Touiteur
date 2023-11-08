<?php

namespace iutnc\touiteur\render;
require_once 'vendor/autoload.php';

use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\exceptions\InvalideTouitException;
use iutnc\touiteur\lists\ListTouit;
use iutnc\touiteur\touit\Touit;
use iutnc\touiteur\touit\User;
use PDO;

class ListTouitRender
{

    private ListTouit $listTouits;

    /**
     * @param ListTouit $listTouits
     */
    public function __construct(ListTouit $listTouits)
    {
        $this->listTouits = $listTouits;
    }

    /**
     * Methode pour afficher la liste des touits
     * @return array : liste des touits
     * @throws InvalideTouitException
     */
    public static function render_home(): array
    {
        $connexion = ConnectionFactory::makeConnection();
        $requete = $connexion->prepare("SELECT texte, date, note, id FROM touite ORDER BY date DESC");
        $requeteImage = $connexion->prepare("SELECT chemin FROM image NATURAL JOIN touite2image WHERE id_touite = ?");

        $requete->execute();

        $touites = [];
        foreach ($requete->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $id = $row['id'];
            $texte = $row['texte'];
            $date = $row['date'];
            $note = $row['note'];
            $pseudo = self::recherche_pseudo($id);


            $requeteImage->bindParam(1, $id);
            $requeteImage->execute();

            $chemin = $requeteImage->fetch(PDO::FETCH_ASSOC);

            if ($chemin !== false) {
                $t = new Touit($id, $texte, $pseudo, $date, 0, $chemin['chemin']);
            } else {
                $t = new Touit($id, $texte, $pseudo, $date, 0);
            }
            array_push($touites, $t);
        }
        return $touites;
    }

    /**
     * Methode pour trouver le pseudo d'un user
     * @param int $id : id du touit
     * @return String : pseudo du user
     */
    private static function recherche_pseudo(int $id): string
    {
        $connexion = ConnectionFactory::makeConnection();
        $requete = $connexion->prepare("SELECT pseudo FROM user NATURAL JOIN user2touite WHERE user2touite.id_touite= ?");
        $requete->bindParam(1, $id);
        $requete->execute();

        return $requete->fetch(PDO::FETCH_ASSOC)['pseudo'];
    }

    /**
     * @throws InvalideTouitException
     */
    public static function render_sub(int $iduser1): array
    {
        $list = [];
        $connexion = ConnectionFactory::makeConnection();
        $requete = $connexion->prepare("SELECT id_user2 FROM abonnement where id_user1 = ?");
        $requete->bindParam(1, $iduser1);
        $requete->execute();
        foreach ($requete->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $requeteTouit = $connexion->prepare("SELECT texte, date, note, chemin, touite.id FROM touite NATURAL JOIN touite2image
                                            NATURAL JOIN image NATURAL JOIN user2touite NATURAL JOIN user WHERE id = ?}");
            $requeteTouit->bindParam(1, $row['id_user2']);
            $requeteTouit->execute();
            foreach ($requete->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $id = $row['id'];
                $texte = $row['texte'];
                $date = $row['date'];
                $note = $row['note'];
                $chemin = $row['chemin'];
                $pseudo = self::recherche_pseudo($row['touite.id']);

                $t = new Touit($id, $texte, $pseudo, $date, $note, $chemin);

                array_push($list, $t);
            }
        }
        return $list;
    }

    /**
     * Methode pour afficher tout les touites d'un user
     * @param User $user : user
     * @return string : tous les touits d'un user
     */
    public function render_user(User $user): string
    {
        $html = "<h3>{$user->pseudo}</h3><br>";
        $html .= "<ul>";
        foreach ($user->listTouits->touits as $touit) {
            $touitrender = new TouitRender($touit);
            $html .= "<li>{$touitrender->render()}</li>";
        }
        $html .= "<br></ul>";
        return $html;


    }


}