<?php

namespace iutnc\touiteur\touit;

use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\exceptions\InvalideTouitException;
use iutnc\touiteur\exceptions\InvalidPropertyNameException;
use iutnc\touiteur\exceptions\TagInexistantException;
use iutnc\touiteur\lists\ListTouit;
use iutnc\touiteur\render\ListTouitRender;
use PDO;

class Tag
{
    private string $nom;
    private ListTouit $listTouits;

    /**
     * @param array $listTouits
     */
    public function __construct(string $nom)
    {
        $this->nom = $nom;
        $this->listTouits = new ListTouit();
    }

    /**
     * @param Touit $t
     * Ajoute un touit à la liste des touits
     */
    public function ajoutTouit(Touit $t): void
    {
        $this->listTouits->add($t);
    }

    /**
     * @param string $at
     * @return mixed
     * @throws InvalidPropertyNameException
     */
    public function __get(string $at): mixed
    {
        if (property_exists($this, $at)) {
            return $this->$at;
        }
        throw new InvalidPropertyNameException("$at: propriété inconnue");
    }

    /**
     * @throws InvalideTouitException
     */
    public static function getTouitbyTag(string $libelle): array
    {
        $db = ConnectionFactory::makeConnection();
        $requeteTouit = $db->prepare("SELECT t.*, i.chemin AS chemin_image
                                            FROM touite t
                                            JOIN touite2tag t2t ON t.id = t2t.id_touite
                                            JOIN tag ta ON t2t.id_tag = ta.id
                                            LEFT JOIN touite2image t2i ON t.id = t2i.id_touite
                                            LEFT JOIN image i ON t2i.id_image = i.id
                                            WHERE ta.libelle = ?");
        $requeteTouit->bindParam(1, $libelle);
        $requeteTouit->execute();

        $list = [];
        foreach ($requeteTouit->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $id = $row['id'];
            $texte = $row['texte'];
            $date = $row['date'];
            $note = $row['note'];
            $chemin = $row['chemin_image'];
            $pseudo = User::recherche_pseudo($id);

            if ($chemin !== null) {
                $t = new Touit($id, $texte, $pseudo, $date, $note, $chemin);
            } else {
                $t = new Touit($id, $texte, $pseudo, $date, $note);
            }
            array_push($list, $t);
        }
        return $list;
    }

    public static function AbonnementTag(string $recherche, int $id): void
    {
        $db = ConnectionFactory::makeConnection();
        $requete = $db->prepare("SELECT id FROM tag WHERE libelle = ?");
        $requete->bindParam(1, $recherche);
        $requete->execute();
        $requete = $requete->fetch(PDO::FETCH_ASSOC);

        if ($requete === false) {
            throw new TagInexistantException("Le tag n'existe pas");
        } else {
            $insert = $db->prepare("INSERT INTO user2tag VALUES (?, ?)");
            $insert->bindParam(1, $id);
            $insert->bindParam(2, $requete['id']);
            $insert->execute();
        }
    }
}