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
     * Methode pour afficher la liste des touits lors que nous ne sommes pas connecté
     * @return array : liste des touits
     * @throws InvalideTouitException
     */
    public static function render_home(): array
    {
        $connexion = ConnectionFactory::makeConnection();
        $requete = $connexion->prepare("SELECT t.texte, t.date, t.note, t.id, u.pseudo AS auteur, i.chemin AS chemin_image
                                        FROM touite t
                                        LEFT JOIN touite2image ti ON t.id = ti.id_touite
                                        LEFT JOIN image i ON ti.id_image = i.id
                                        LEFT JOIN user2touite u2t ON t.id = u2t.id_touite
                                        LEFT JOIN user u ON u2t.id_user = u.id
                                        ORDER BY t.date desc ");
        $requete->execute();

        $touites = [];
        foreach ($requete->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $t = new Touit($row['id'], $row['texte'], $row['auteur'], $row['date'], $row['note'], $row['chemin_image']);
            array_push($touites, $t);
        }
        return $touites;
    }

    /**
     * Methode pour afficher la liste des touits de nos abonnements
     * @throws InvalideTouitException
     */
    public static function render_sub(int $iduser1): array
    {
        $connexion = ConnectionFactory::makeConnection();
        $requete = $connexion->prepare("SELECT t.texte, t.date, t.note, u.pseudo AS auteur, i.chemin, t.id
                                    FROM touite t
                                    LEFT JOIN touite2image ti ON t.id = ti.id_touite
                                    LEFT JOIN image i ON ti.id_image = i.id
                                    LEFT JOIN user2touite u2t ON t.id = u2t.id_touite
                                    LEFT JOIN user u ON u2t.id_user = u.id
                                    LEFT JOIN touite2tag t2t ON t.id = t2t.id_touite
                                    WHERE (u2t.id_user IN (SELECT id_user2 FROM abonnement WHERE id_user1 = ?) OR u2t.id_user IN (SELECT id_user FROM user2tag WHERE id_tag IN (SELECT id_tag FROM user2tag WHERE id_user = ?))) AND u2t.id_user != ?
                                    ORDER BY t.date desc");
        $requete->bindParam(1, $iduser1);
        $requete->bindParam(2, $iduser1);
        $requete->bindParam(3, $iduser1);
        $requete->execute();

        $list = [];
        foreach ($requete->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $t = new Touit($row['id'], $row['texte'], $row['auteur'], $row['date'], $row['note'], $row['chemin']);
            array_push($list, $t);
        }
        return $list;
    }


}