<?php

namespace iutnc\touiteur\touit;
require_once 'vendor/autoload.php';

use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\exceptions\InvalideTouitException;
use iutnc\touiteur\exceptions\InvalidPropertyNameException;
use iutnc\touiteur\exceptions\TagDejaSuiviException;
use iutnc\touiteur\exceptions\TouitInexistantException;
use iutnc\touiteur\lists\ListTags;
use iutnc\touiteur\lists\ListTouit;
use iutnc\touiteur\lists\ListUser;
use PDO;

class User
{

    private string $pseudo;
    private string $nom;
    private string $email;
    private string $mdp;
    protected int $role; //role de l'utilisateur
    private string $id;

    private ListUser $abonnements; //liste des abonnements du membre
    private ListUser $abonnés; //liste des abonnés du membre

    private ListTouit $listTouits; //liste des touits du membre
    private ListTags $tagsSuivis; //liste des tags suivis par le membre
    protected ListTouit $touitPubliés; //liste des touits que peut consulter un utilisateur

    public static int $STANDARD_ROLE = 1;
    public static int $ADMIN_ROLE = 100;

    /**
     * @param string $pseudo
     * @param string $nom
     * @param string $email
     * @param int $role
     */
    public function __construct(int $id, string $pseudo, string $nom, string $email, int $role)
    {
        $this->id = $id;
        $this->pseudo = $pseudo;
        $this->nom = $nom;
        $this->email = $email;
        $this->role = $role;
        $this->listTouits = new ListTouit();
    }

    /**
     * Methode pour permettre au membre de créer un nouveau touit
     * @return void
     * @throws InvalideTouitException
     */
    public function publierTouit(string $t, string $fileimage = ''): void
    {
        $connection = ConnectionFactory::makeConnection();
        $date = gmdate('Y-m-d');
        $requete = $connection->prepare("INSERT INTO touite (texte, date,note) VALUES (?,?,?)");
        $note = 0;
        $requete->bindParam(1, $t);
        $requete->bindParam(2, $date);
        $requete->bindParam(3, $note);
        $requete->execute();

        $id = $connection->prepare("SELECT max(id) FROM touite");
        $id->execute();
        $id = $id->fetch(PDO::FETCH_ASSOC)['max(id)'];

        $lienUser2Touit = $connection->prepare("INSERT INTO user2touite (id_touite,id_user) VALUES (?, ?)");
        $lienUser2Touit->bindParam(1, $id);
        $lienUser2Touit->bindParam(2, $this->id);
        $lienUser2Touit->execute();


        /**
         * Insert dans la table image une image
         */

        $insertionImage = $connection->prepare("INSERT INTO image (chemin) VALUES (?)");
        $insertionImage->bindParam(1, $fileimage);
        $insertionImage->execute();

        $idImage = $connection->prepare("SELECT max(id) FROM image");
        $idImage->execute();
        $idImage = $idImage->fetch(PDO::FETCH_ASSOC)['max(id)'];

        $lienTouit2Image = $connection->prepare("INSERT INTO touite2image (id_touite, id_image) VALUES (?,?)");
        $lienTouit2Image->bindParam(1, $id);
        $lienTouit2Image->bindParam(2, $idImage);

        preg_match_all('/#(\w+)/', $t, $matches);
        $tags = $matches[1];

        if (empty($tags)) {
            $tags = [''];
        } else {
            foreach ($tags as $tag) {
                $tagObj = new Tag($tag);

                if (!$this->tagsExiste($tagObj)) {
                    $insertTag = $connection->prepare("INSERT INTO tags (tag) VALUES (?)");
                    $insertTag->bindParam(1, $tag);
                    $insertTag->execute();
                }
            }
        }
        //$touit = new Touit($id, $t, $this->pseudo, gmdate('Y-m-d'), 0, $fileimage);
    }

    /**
     * Methode pour permettre au membre de supprimer un touit
     * @return void
     */
    public function supprimerTouit(Touit $touit): void
    {
        $connection = ConnectionFactory::makeConnection();
        $requete = $connection->prepare("DELETE FROM touit WHERE id = ?");
    }

    /**
     * Methode qui permet au membre de liker un touit
     * @param Touit $t
     * @return void
     */
    public function liker(Touit $t): void
    {
        $t->__set("note", $t->__get("note") + 1);
    }

    /**
     * Methode qui permet au membre de disliker un touit
     * @param Touit $t
     * @return void
     */
    public function disliker(Touit $t): void
    {
        $t->__set("note", $t->__get("note") - 1);
    }

    /**
     * Methode pour permettre au membre de suivre un tag
     * @param Tag $t
     * @return void
     */
    public function suivreTag(Tag $t): void
    {
        $this->tagsSuivis->ajoutTag($t);
    }

    /**
     * Methode pour récuperer la listes des touits d'un abonnement
     * @param User $user
     * @return ListTouit liste des touits d'un utilisateur donné
     */
    public function getTouitUser(User $user): ListTouit
    {
        return $user->__get("listTouits");
    }


    /**
     * @param Tag $tag
     * @return bool
     * Methode pour verifier si un tag existe dans la base de données
     */
    public function tagsExiste(Tag $tag): bool
    {
        $connextion = ConnectionFactory::makeConnection();
        $requete = $connextion->prepare("SELECT libelle FROM tag");
        $requete->execute([$tag->__get("tag")]);
        $resultat = $requete->fetch(PDO::FETCH_ASSOC);
        if ($resultat) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Methode pour récuperer la liste des touits d'un tag donné
     * @param Tag $tag
     * @return ListTouit liste des touits d'un tag donné
     */
    public function getTags(Tag $tag): ListTouit
    {
        return $tag->getTouits();
    }


    public function __get(string $at): mixed
    {
        if (property_exists($this, $at)) {
            return $this->$at;
        }
        throw new InvalidPropertyNameException("$at: propriété inconnue");
    }

}