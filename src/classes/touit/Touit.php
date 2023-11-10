<?php

namespace iutnc\touiteur\touit;

use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\exceptions\InvalideTouitException;
use iutnc\touiteur\exceptions\InvalidPropertyNameException;
use mysql_xdevapi\Exception;
use PDO;

require_once 'vendor/autoload.php';

class Touit
{
    /**
     * @var string $texte : Texte d'un touit
     */
    private string $texte;
    /**
     * @var String pseudo : Pseudo de l'auteur du touit
     */
    private string $pseudo;
    /**
     * @var string $date : Date de publication du touit
     */
    private string $date;
    /**
     * @var int $note : Nombre de likes du touit
     */
    private int $note;
    /**
     * @var array $nbTags : Nombre de tags du touit
     */
    private array $nbTags;
    /**
     * @var ?string $image : Image du touit, pas obligatoire
     */
    private ?string $image;
    /**
     * @var int $id : Identifiant du touit
     */
    private int $id;

    /**
     * @param string $text
     * @param User $user
     * @param string $date
     * @param string $image
     * @throws InvalideTouitException
     * Constructeur de la classe Touit qui permet de créer un touit qui prend en paramètre
     * un texte, le pseudo de l'auteur du touit, la date de publication du touit et une possible image
     */

    public function __construct(int $id, string $text, string $pseudo, string $date, int $note = 0, ?string $image = '')
    {

        if (strlen($text) > 235) {
            throw new InvalideTouitException("Le touit est trop long");
        } else {
            $this->texte = $text;
        }
        $this->id = $id;
        $this->pseudo = $pseudo;
        $this->date = $date;
        $this->note = $note;
        $this->nbTags = [];
        $this->image = $image;
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

    public function __set(string $at, mixed $val = null)
    {
        if (property_exists($this, $at)) {
            $this->$at = $val;
        } else {
            throw new InvalidPropertyNameException (get_called_class() . " attribut invalid" . $at);
        }
    }

    /**
     * @throws InvalideTouitException
     */
    public static function getTouit(int $id): Touit
    {
        $db = ConnectionFactory::makeConnection();
        $requete = $db->prepare("SELECT t.texte, t.date, t.note, u.pseudo AS auteur, i.chemin, t.id
                                FROM touite t
                                LEFT JOIN touite2image ti ON t.id = ti.id_touite
                                LEFT JOIN image i ON ti.id_image = i.id
                                LEFT JOIN user2touite u2t ON t.id = u2t.id_touite
                                LEFT JOIN user u ON u2t.id_user = u.id
                                WHERE t.id = ?");
        $requete->bindParam(1, $id);
        $requete->execute();
        $row = $requete->fetch(\PDO::FETCH_ASSOC);

        $t = new Touit($row['id'], $row['texte'], $row['auteur'], $row['date'], $row['note'], $row['chemin']);

        return $t;
    }

    public static function getIdUserByIdTouit(int $id): int
    {
        $db = ConnectionFactory::makeConnection();
        $requete = $db->prepare("SELECT id_user
                                FROM user2touite u2t
                                WHERE id_touite = ?");
        $requete->bindParam(1, $id);
        $requete->execute();
        return $requete->fetch(\PDO::FETCH_ASSOC)['id_user'];
    }

    public static function liker(int $idUser, int $idTouit, int $note): void
    {
        $db = ConnectionFactory::makeConnection();
        $requeteexitante = $db->prepare("SELECT id_user, id_touite FROM evaluer where id_user = ? and id_touite = ?");
        $requeteexitante->bindParam(1, $idUser);
        $requeteexitante->bindParam(2, $idTouit);
        $requeteexitante->execute();

        $nombreLike = Touit::getCountLike($idUser, $idTouit);

        $requete = $db->prepare("UPDATE touite SET note = ? + 1 WHERE id = ?");
        $requete->bindParam(1, $nombreLike);
        $requete->bindParam(2, $idTouit);
        $requete->execute();

        $requeteexitante = $requeteexitante->fetch(\PDO::FETCH_ASSOC);
        if ($requeteexitante === false) {
            $requeteEvaluer = $db->prepare("INSERT INTO EVALUER (`id_user`, `id_touite`, `note`) VALUES (?, ?, ?)");
            $requeteEvaluer->bindParam(1, $idUser);
            $requeteEvaluer->bindParam(2, $idTouit);
            $requeteEvaluer->bindParam(3, $note);
            $requeteEvaluer->execute();
        } else {
            $requeteEvaluer = $db->prepare("UPDATE EVALUER SET NOTE = ? WHERE id_user = ? and id_touite = ?");
            $requeteEvaluer->bindParam(1, $note);
            $requeteEvaluer->bindParam(2, $idUser);
            $requeteEvaluer->bindParam(3, $idTouit);
            $requeteEvaluer->execute();
        }
    }

    /**
     * Methode qui permet au user de disliker un touit
     *
     * @param int $id : id du touit
     * @return void
     */
    public static function disliker(int $idUser, int $idTouit, int $note): void
    {
        $db = ConnectionFactory::makeConnection();
        $requeteexitante = $db->prepare("SELECT id_user, id_touite FROM evaluer where id_user = ? and id_touite = ?");
        $requeteexitante->bindParam(1, $idUser);
        $requeteexitante->bindParam(2, $idTouit);
        $requeteexitante->execute();

        $nombreLike = Touit::getCountLike($idUser, $idTouit);

        $requete = $db->prepare("UPDATE touite SET note = ? - 1 WHERE id = ?");
        $requete->bindParam(1, $nombreLike);
        $requete->bindParam(2, $idTouit);
        $requete->execute();

        $requeteexitante = $requeteexitante->fetch(\PDO::FETCH_ASSOC);
        if ($requeteexitante === false) {
            $requeteEvaluer = $db->prepare("INSERT INTO EVALUER (`id_user`, `id_touite`, `note`) VALUES (?, ?, ?)");
            $requeteEvaluer->bindParam(1, $idUser);
            $requeteEvaluer->bindParam(2, $idTouit);
            $requeteEvaluer->bindParam(3, $note);
            $requeteEvaluer->execute();
        } else {
            $requeteEvaluer = $db->prepare("UPDATE EVALUER SET NOTE = ? WHERE id_user = ? and id_touite = ?");
            $requeteEvaluer->bindParam(1, $note);
            $requeteEvaluer->bindParam(2, $idUser);
            $requeteEvaluer->bindParam(3, $idTouit);
            $requeteEvaluer->execute();
        }
    }

    private static function getCountLike(int $idUser, int $idTouit): int
    {
        $db = ConnectionFactory::makeConnection();
        $requete = $db->prepare("SELECT COUNT(id_user) as nb FROM evaluer where id_touite = ? and id_user != ?");
        $requete->bindParam(1, $idTouit);
        $requete->bindParam(2, $idUser);
        $requete->execute();
        $nb = $requete->fetch(\PDO::FETCH_ASSOC)['nb'];
        if ($nb === false) {
            return 0;
        }
        return $nb;
    }

    public static function supprimerTouit(int $id): void
    {
        $connection = ConnectionFactory::makeConnection();

        //requepère l'id de l'image
        $RecupIdImage = $connection->prepare("SELECT id_image FROM touite2image WHERE id_touite = ?");
        $RecupIdImage->bindParam(1, $id);
        $RecupIdImage->execute();

        $SuppLienImage = $connection->prepare("DELETE FROM touite2image WHERE id_touite = ?");
        $SuppLienImage->bindParam(1, $id);
        $SuppLienImage->execute();

        //supprime l'image
        $idImage = $RecupIdImage->fetch(PDO::FETCH_ASSOC);
        $SuppImage = $connection->prepare("DELETE FROM image WHERE id = ?");
        $SuppImage->bindParam(1, $idImage['id_image']);
        $SuppImage->execute();


        $SuppLienTag = $connection->prepare("DELETE FROM touite2tag WHERE id_touite = ?");
        $SuppLienTag->bindParam(1, $id);
        $SuppLienTag->execute();

        $SuppLienUser = $connection->prepare("DELETE FROM user2touite WHERE id_touite = ?");
        $SuppLienUser->bindParam(1, $id);
        $SuppLienUser->execute();

        $requete = $connection->prepare("SELECT id_user FROM evaluer WHERE id_touite = ?");
        $requete->bindParam(1, $id);
        $requete->execute();

        $requeteDeleteNote = $connection->prepare("DELETE FROM evaluer WHERE id_user = ?");
        foreach ($requete->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $requeteDeleteNote->bindParam(1, $row['id_user']);
            $requeteDeleteNote->execute();
        }

        $SuppTouit = $connection->prepare("DELETE FROM touite WHERE id = ?");
        $SuppTouit->bindParam(1, $id);
        $SuppTouit->execute();
    }
}