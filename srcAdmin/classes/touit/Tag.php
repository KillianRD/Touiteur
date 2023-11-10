<?php

namespace iutnc\touiteur\admin\touit;

use iutnc\touiteur\admin\db\ConnectionFactory;
use iutnc\touiteur\admin\exceptions\InvalidPropertyNameException;
use PDO;

class Tag
{
    private string $nom;

    public function __construct(string $n)
    {
        $this->nom = $n;
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
     * Méthode qui permet de récupérer la liste des tags avec le nombre de fois qu'ils sont utilisés
     *
     * @return string : liste des tags
     */
    public static function ListTag(): string
    {
        $db = ConnectionFactory::makeConnection();
        $requete = $db->prepare("SELECT tag.libelle, COUNT(touite2tag.id_tag) AS nb_fois
                                        FROM tag JOIN touite2tag ON tag.id = touite2tag.id_tag
                                        GROUP BY tag.libelle ORDER BY nb_fois ASC");
        $requete->execute();
        $t = "";

        foreach ($requete->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $t .= "<p>Nom du tag : #" . $row['libelle'] . ", nombre de touites associés : " . $row['nb_fois'] . "</p>";
        }
        return $t;
    }
}