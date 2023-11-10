<?php

namespace iutnc\touiteur\touit;
require_once 'vendor/autoload.php';

use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\exceptions\InvalideTouitException;
use iutnc\touiteur\exceptions\InvalidPropertyNameException;
use iutnc\touiteur\exceptions\UserInexistantException;
use iutnc\touiteur\lists\ListTags;
use iutnc\touiteur\lists\ListTouit;
use iutnc\touiteur\lists\ListUser;
use iutnc\touiteur\render\TouitRender;
use iutnc\touiteur\render\UserRender;
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
        $filtreTouite = filter_var($t, FILTER_SANITIZE_SPECIAL_CHARS);
        $connection = ConnectionFactory::makeConnection();
        $requete = $connection->prepare("INSERT INTO  touite(texte, date,note) VALUES (?,?,?)");
        $date = gmdate('Y-m-d');
        $note = 0;
        $requete->bindParam(1, $filtreTouite);
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
                $filtreTag= filter_var($tag, FILTER_SANITIZE_SPECIAL_CHARS);
                $tagObj = new Tag($filtreTag);

                if (!$this->tagsExiste($tagObj)) {
                    $insertTag = $connection->prepare("INSERT INTO tag (libelle) VALUES (?)");
                    $insertTag->bindParam(1, $tag);
                    $insertTag->execute();
                }
            }
            foreach ($tags as $tag) {
                $filtreTag= filter_var($tag, FILTER_SANITIZE_SPECIAL_CHARS);
                $idtag = $connection->prepare("SELECT id FROM TAG where libelle = ?");
                $idtag->bindParam(1, $filtreTag);
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
     * Methode qui permet d'afficher les touits d'un user
     *
     * @throws InvalideTouitException
     */
    private static function render_Profil_Touit(int $id): array
    {
        $db = ConnectionFactory::makeConnection();
        $requete = $db->prepare("SELECT t.texte, t.date, t.note, u.pseudo AS auteur, i.chemin, t.id
                                FROM touite t
                                LEFT JOIN touite2image ti ON t.id = ti.id_touite
                                LEFT JOIN image i ON ti.id_image = i.id
                                JOIN user2touite u2t ON t.id = u2t.id_touite
                                LEFT JOIN user u ON u2t.id_user = u.id
                                WHERE u2t.id_user = ?");
        $requete->bindParam(1, $id);
        $requete->execute();

        $list = [];
        foreach ($requete->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $t = new Touit($row['id'], $row['texte'], $row['auteur'], $row['date'], $row['note'], $row['chemin']);
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
                                        JOIN abonnement a ON u.id = a.id_user1
                                        WHERE a.id_user2 = ?");
        $requete->bindParam(1, $id);
        $requete->execute();

        $listSub = [];
        foreach ($requete->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $u = new User($row['id'], $row['pseudo'], $row['nom'], $row['prenom'], $row['email'], $row['role']);
            array_push($listSub, $u);
        }
        return $listSub;
    }

    public static function render_Follow_Profil(int $id): array
    {
        $db = ConnectionFactory::makeConnection();
        $requete = $db->prepare("SELECT id, pseudo, nom, prenom, email, role FROM user u
                                        JOIN abonnement a ON u.id = a.id_user2
                                        WHERE a.id_user1= ?");
        $requete->bindParam(1, $id);
        $requete->execute();

        $listSub = [];
        foreach ($requete->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $u = new User($row['id'], $row['pseudo'], $row['nom'], $row['prenom'], $row['email'], $row['role']);
            array_push($listSub, $u);
        }
        return $listSub;
    }


    private static function getInfo(int $id): string
    {
        $db = ConnectionFactory::makeConnection();
        $requete = $db->prepare("SELECT pseudo, nom, prenom, email, role FROM user WHERE id = ?");
        $requete->bindParam(1, $id);
        $requete->execute();
        $row = $requete->fetch(PDO::FETCH_ASSOC);

        $render = new UserRender(new User($id, $row['pseudo'], $row['nom'], $row['prenom'], $row['email'], $row['role']));
        return $render->render();
    }

    public static function getIdByPseudo(string $pseudo): int
    {
        $db = ConnectionFactory::makeConnection();
        $requete = $db->prepare("SELECT id FROM user WHERE pseudo = ?");
        $requete->bindParam(1, $pseudo);
        $requete->execute();
        $id = $requete->fetch(PDO::FETCH_ASSOC);
        if($id !== false){
            return $id['id'];
        } else {
            throw new UserInexistantException("La personne que vous cherchez n'existe pas");
        }
    }

    /**
     * @throws InvalideTouitException
     */
    public static function renderProfil(int $id): string
    {
        $html = '';
        $u = unserialize($_SESSION['user']);

        if($id == $u->id){

        } else if(User::CheckUserFollow($id, $u->id)){
            $html = "<a href='?action=desabonner&id={$id}'>Abonné</a>";
        } else if(!User::CheckUserFollow($id, $u->id)){
            $html = "<a href='?action=suivre&id={$id}'>S'abonner</a>";
        }

        $html .= User::getInfo($id);
        $html .= <<<END
            <a href='?action=abonne'>Abonné</a>
            <a href='?action=abonnement'>Abonnement</a>
        END;

        $listTouit = User::render_Profil_Touit($id);
        foreach ($listTouit as $touit) {
            $render = new TouitRender($touit);
            $html .= $render->render(1);
        }

        return $html;
    }

    /**
     * Methode qui permet de savoir si le user connecté est abonné à un autre user
     *
     * @param int $idUser : id du user
     * @param int $idSub : id du user abonné
     * @return bool : true si abonné
     */

    public static function CheckUserFollow(int $idUser, int $idSub): bool
    {
        $db = ConnectionFactory::makeConnection();
        $requete = $db->prepare("SELECT id_user2 FROM abonnement WHERE id_user1 = ? and id_user2 = ?");
        $requete->bindParam(1, $idSub);
        $requete->bindParam(2, $idUser);
        $requete->execute();
        $id = $requete->fetch(PDO::FETCH_ASSOC);

        if ($id !== false) return true;
        return false;
    }

    /**
     * Methode qui permet au user de ne plus suivre un autre user
     *
     * @param int $id : id du user
     * @return void
     */
    public static function nePlusSuivreUser(int $id1, int $id2): void
    {
        $connection = ConnectionFactory::makeConnection();
        $requete = $connection->prepare("DELETE FROM abonnement WHERE id_user1 = ? and id_user2 = ?");
        $requete->bindParam(1, $id1);
        $requete->bindParam(2, $id2);
        $requete->execute();
    }


    /**
     * Methode qui permet au user de suivre un autre user
     *
     * @param int $id : id du user
     * @return void
     */
    public static function suivreUser(int $id1, int $id2): void
    {
        $connection = ConnectionFactory::makeConnection();
        $requete = $connection->prepare("INSERT INTO abonnement (id_user1, id_user2) VALUES (?, ?)");
        $requete->bindParam(1, $id1);
        $requete->bindParam(2, $id2);
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