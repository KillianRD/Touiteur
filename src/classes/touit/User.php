<?php

namespace iutnc\touiteur\touit;
require_once 'vendor/autoload.php';

use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\exceptions\InvalideTouitException;
use iutnc\touiteur\exceptions\InvalidPropertyNameException;
use iutnc\touiteur\lists\ListTags;
use iutnc\touiteur\lists\ListTouit;
use iutnc\touiteur\lists\ListUser;
use PDO;

class User
{

    private string $pseudo;
    private string $nom;
    private string $prenom;
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
    public function __construct(int $id, string $pseudo, string $nom, string $prenom, string $email, int $role)
    {
        $this->id = $id;
        $this->pseudo = $pseudo;
        $this->nom = $nom;
        $this->prenom = $prenom;
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
        $requete = $connection->prepare("INSERT INTO touite (texte, date,note) VALUES (?,?,?)");
        $date = gmdate('Y-m-d');
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

         //Insert dans la table image une image
        $insertionImage = $connection->prepare("INSERT INTO image (chemin) VALUES (?)");
        $insertionImage->bindParam(1, $fileimage);
        $insertionImage->execute();

        $idImage = $connection->prepare("SELECT max(id) FROM image");
        $idImage->execute();
        $idImage = $idImage->fetch(PDO::FETCH_ASSOC)['max(id)'];

        $lienTouit2Image = $connection->prepare("INSERT INTO touite2image (id_touite, id_image) VALUES (?,?)");
        $lienTouit2Image->bindParam(1, $id);
        $lienTouit2Image->bindParam(2, $idImage);
        $lienTouit2Image->execute();

        preg_match_all('/#(\w+)/', $t, $matches);
        $tags = $matches[1];

        if (empty($tags)) {
            $tags = [''];
        } else {
            foreach ($tags as $tag) {
                $tagObj = new Tag($tag);

                if (!$this->tagsExiste($tagObj)) {
                    $insertTag = $connection->prepare("INSERT INTO tag (libelle) VALUES (?)");
                    $insertTag->bindParam(1, $tag);
                    $insertTag->execute();
                }
            }
            foreach ($tags as $tag) {
                $idtag = $connection->prepare("SELECT id FROM TAG where libelle = ?");
                $idtag->bindParam(1, $tag);
                $idtag->execute();
                $idtag = $idtag->fetch(PDO::FETCH_ASSOC);
                $insertTouite2Tag = $connection->prepare("INSERT INTO touite2tag (id_touite, id_tag) VALUES (?, ?)");
                $insertTouite2Tag->bindParam(1, $id);
                $insertTouite2Tag->bindParam(2, $idtag['id']);
                $insertTouite2Tag->execute();
            }
        }
        //$touit = new Touit($id, $t, $this->pseudo, gmdate('Y-m-d'), 0, $fileimage);
    }

    /**
     * Methode pour trouver le pseudo d'un user
     *
     * @param int $id : id du touit
     * @return String : pseudo du user
     */
    public static function recherche_pseudo(int $id): string
    {
        $connexion = ConnectionFactory::makeConnection();
        $requete = $connexion->prepare("SELECT u.pseudo FROM user u
                                        JOIN user2touite u2t ON u.id = u2t.id_user
                                        WHERE u2t.id_touite = ?");
        $requete->bindParam(1, $id);
        $requete->execute();

        return $requete->fetch(PDO::FETCH_ASSOC)['pseudo'];
    }

    /**
     * Methode qui permet d'afficher les touits d'un user
     *
     * @throws InvalideTouitException
     */
    public static function render_Profil_Touit(int $id): array
    {
        $db = ConnectionFactory::makeConnection();
        $requete = $db->prepare("SELECT texte, date, note, chemin, touite.id FROM touite NATURAL JOIN touite2image
                                            NATURAL JOIN image NATURAL JOIN user2touite where id_user = ?");
        $requete->bindParam(1, $id);
        $requete->execute();

        $list = [];
        foreach ($requete->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $id = $row['id'];
            $texte = $row['texte'];
            $date = $row['date'];
            $note = $row['note'];
            $chemin = $row['chemin'];
            $pseudo = User::recherche_pseudo($row['id']);

            $t = new Touit($id, $texte, $pseudo, $date, $note, $chemin);

            array_push($list, $t);
        }
        return $list;
    }

    /**
     * Methode qui peremet d'affichier les abonnés d'un user
     *
     * @param int $id : id du user
     * @return array : liste des abonnés
     */
    public static function render_Sub_Profil(int $id): array
    {
        $db = ConnectionFactory::makeConnection();
        $requete = $db->prepare("SELECT id, pseudo, nom, prenom, email, role FROM user u
                                        JOIN abonnement a ON u.id = a.id_user2
                                        WHERE a.id_user1 = ?");
        $requete->bindParam(1, $id);
        $requete->execute();

        $listSub = [];
        foreach ($requete->fetch(PDO::FETCH_ASSOC) as $row) {
            $u = new User($row['id'], $row['pseudo'], $row['nom'], $row['prenom'], $row['email'], $row['role']);
            array_push($listSub, $u);
        }
        return $listSub;
    }

    /**
     * Methode qui permet de savoir si un user est abonné à un autre user
     *
     * @param int $idUser : id du user
     * @param int $idSub : id du user abonné
     * @return bool : true si abonné
     */
    public static function CheckUserFollow(int $idUser, int $idSub): bool
    {
        $db = ConnectionFactory::makeConnection();
        $requete = $db->prepare("SELECT id_user2 FROM abonnement WHERE id_user1 = ?");
        $requete->bindParam(1, $idSub);
        $requete->execute();

        if ($requete->fetchAll(PDO::FETCH_ASSOC)['id_user2'] === $idUser) return true;
        return false;
    }

    /**
     * Methode pour permettre au user de supprimer un touit
     * @param int $id : id du touit
     * @return void
     */
    public function supprimerTouit(int $id): void
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
        $idImage = $RecupIdImage->fetch(PDO::FETCH_ASSOC)['id_image'];
        $SuppImage = $connection->prepare("DELETE FROM image WHERE id = ?");
        $SuppImage->bindParam(1, $idImage);
        $SuppImage->execute();


        $SuppLienTag = $connection->prepare("DELETE FROM touite2tag WHERE id_touite = ?");
        $SuppLienTag->bindParam(1, $id);
        $SuppLienTag->execute();

        $SuppLienUser = $connection->prepare("DELETE FROM user2touite WHERE id_touite = ?");
        $SuppLienUser->bindParam(1, $id);
        $SuppLienUser->execute();

        $SuppTouit = $connection->prepare("DELETE FROM touit WHERE id = ?");
        $SuppTouit->bindParam(1, $id);
        $SuppTouit->execute();
    }


    /**
     * Methode pour permettre au user de liker un touit
     *
     * @param int $id : id du touit
     * @return void
     */
    public function liker(int $id): void
    {
        $connection = ConnectionFactory::makeConnection();
        $requete = $connection->prepare("UPDATE touite SET note = note + 1 WHERE id = ?");
        $requete->bindParam(1, $id);
        $requete->execute();
    }

    /**
     * Methode qui permet au user de disliker un touit
     *
     * @param int $id : id du touit
     * @return void
     */
    public function disliker(int $id): void
    {
        $connection = ConnectionFactory::makeConnection();
        $requete = $connection->prepare("UPDATE touite SET note = note + 1 WHERE id = ?");
        $requete->bindParam(1, $id);
        $requete->execute();
    }

    /**
     * Methode qui permet au user de suivre un tag
     *
     * @param int $id : id du tag
     * @return void
     */
    public function suivreTag(int $id): void
    {
        $connection = ConnectionFactory::makeConnection();
        $requete = $connection->prepare("INSERT INTO user2tag (id_user, id_tag) VALUES (?, ?)");
        $requete->bindParam(1, $this->id);
        $requete->bindParam(2, $id);

    }

    /**
     * Methode qui permet au user de ne plus suivre un tag
     *
     * @param int $id : id du tag
     * @return void
     */
    public function nePlusSuivreTag(int $id): void
    {
        $connection = ConnectionFactory::makeConnection();
        $requete = $connection->prepare("DELETE FROM user2tag WHERE id_user = ? AND id_tag = ?");
        $requete->bindParam(1, $this->id);
        $requete->bindParam(2, $id);
        $requete->execute();
    }


    /**
     * Methode qui permet au user de suivre un autre user
     *
     * @param int $id : id du user
     * @return void
     */
    public function suivreUser(int $id): void
    {
        $connection = ConnectionFactory::makeConnection();
        $requete = $connection->prepare("INSERT INTO abonnement (id_user1, id_user2) VALUES (?, ?)");
        $requete->bindParam(1, $this->id);
        $requete->bindParam(2, $id);
        $requete->execute();
    }

    /**
     * Methode qui permet au user de ne plus suivre un autre user
     *
     * @param int $id : id du user
     * @return void
     */
    public function nePlusSuivreUser(int $id): void
    {
        $connection = ConnectionFactory::makeConnection();
        $requete = $connection->prepare("DELETE FROM abonnment (id_use1, id_user2) VALUES (?, ?))");
        $requete->bindParam(1, $this->id);
        $requete->bindParam(2, $id);
        $requete->execute();
    }

    /**
     * Methode qui permet de récuperer tous des touits d'un user
     *
     * @param User $user : user dont on veut récuperer les touits
     * @return ListTouit liste des touits du user
     */
    public function getTouitUser(User $user): ListTouit
    {
        return $user->__get("listTouits");
    }


    /**
     *  Methode qui permet de verifier si un tag existe dans la base de données
     *
     * @param Tag $tag : tag dont on veut vérifier l'existence
     * @return bool : true si le tag existe
     */
    public function tagsExiste(Tag $tag): bool
    {
        $connextion = ConnectionFactory::makeConnection();
        $requete = $connextion->prepare("SELECT libelle FROM tag where libelle = ?");
        $requete->execute([$tag->nom]);
        $resultat = $requete->fetch(PDO::FETCH_ASSOC);
        if ($resultat) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Methode qui permet de récuperer la liste des touits d'un tag donné
     *
     * @param Tag $tag : tag dont on veut récuperer les touits
     * @return ListTouit : liste des touits du tag
     */
    public function getTags(Tag $tag): ListTouit
    {
        return $tag->getTouits();
    }


    /**
     * Mmethode qui permet de récuperer la liste des tags suivis par un user
     *
     * @param string $at : nom de la propriété
     * @return mixed : liste des tags suivis par le user
     * @throws InvalidPropertyNameException : si la propriété n'existe pas
     */
    public function __get(string $at): mixed
    {
        if (property_exists($this, $at)) {
            return $this->$at;
        }
        throw new InvalidPropertyNameException("$at: propriété inconnue");
    }

}