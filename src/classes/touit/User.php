<?php

namespace iutnc\touiteur\touit;
require_once 'vendor/autoload.php';

use iutnc\touiteur\exceptions\InvalidPropertyNameException;
use iutnc\touiteur\exceptions\TagDejaSuiviException;
use iutnc\touiteur\exceptions\TouitInexistantException;
use iutnc\touiteur\lists\ListTags;
use iutnc\touiteur\lists\ListTouit;
use iutnc\touiteur\lists\ListUser;

class User {

    private string $pseudo;
    private string $nom;
    private string $email;
    private string $mdp;
    protected int $role; //role de l'utilisateur

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
     */
    public function __construct(string $pseudo, string $nom, string $email, int $role) {
        $this->pseudo = $pseudo;
        $this->nom = $nom;
        $this->email = $email;
        $this->role = $role;
    }

    /**
     * Methode pour permettre au membre de créer un nouveau touit
     * @return void
     */
    public function publierTouit(string $t, string $fileimage ='') :void {
        $touit = new Touit($t,$this->pseudo,date("d-m-Y H:i:s"),$fileimage);
        $this->listTouits->add($touit);
    }

    /**
     * Methode pour permettre au membre de supprimer un touit
     * @return void
     */
    public function supprimerTouit(Touit $touit) :void {
        $this->listTouits->suppr($touit);
    }

    /**
     * Methode qui permet au membre de liker un touit
     * @param Touit $t
     * @return void
     */
    public function liker(Touit $t): void {
        $note = $t->__get("note");
        $t->setNote($note ++) ;
    }

    /**
     * Methode qui permet au membre de disliker un touit
     * @param Touit $t
     * @return void
     */
    public function dislike(Touit $t) : void {
        $note = $t->__get("note");
        $t->setNote($note --) ;
    }

    /**
     * Methode pour permettre au membre de suivre un tag
     * @param Tag $t
     * @return void
     */
    public function suivreTag(Tag $t) :void {
        $this->tagsSuivis->ajoutTag($t);
    }

    /**
     * Methode pour récuperer la listes des touits d'un utilisateur donné
     * @param User $user
     * @return array liste des touits d'un utilisateur donné
     */
    public function getTouitUser(User $user): ListTouit {
        return $user->__get("listTouits");
    }

    /**
     * Methode pour récuperer la liste des touits d'un tag donné
     * @param Tag $tag
     * @return array liste des touits d'un tag donné
     */
    public function getTags(Tag $tag) : array {
        return $tag->getTouits();
    }

    public function __get(string $at): mixed {
        if (property_exists($this, $at)) {
            return $this->$at;
        }
        throw new InvalidPropertyNameException("$at: propriété inconnue");
    }
    
}