<?php

namespace iutnc\touiteur\touit;

class User {

    private string $pseudo;
    private string $nom;
    private string $email;
    private string $mdp;
    private array $abonnements = []; //liste des abonnements du membre
    private array $abonnés = []; //liste des abonnés du membre
    private array $tagsSuivis = []; //liste des tags suivis par le membre
    private array $listTouits = []; //liste des touits du membre
    protected array $touitPubliés = [] ; //liste des touits que peut consulter un utilisateur
    protected int $role; //role de l'utilisateur
    /**
     * @param string $pseudo
     * @param string $nom
     * @param string $email
     * @param string $mdp
     */
    public function __construct(string $pseudo, string $nom, string $email, string $mdp) {
        $this->pseudo = $pseudo;
        $this->nom = $nom;
        $this->email = $email;
        $this->mdp = $mdp;
    }

    /**
     * Methode pour permettre au membre de créer un nouvau touit
     * @return void
     */
    public function publierTouit() :void {

    }

    /**
     * Methode pour permettre au membre de supprimer un touit
     * @return void
     */
    public function supprimerTouit() :void {

    }

    /**
     * Methode qui permet au membre de liker un touit
     * @param Touit $t
     * @return void
     */
    public function liker(Touit $t): void {

    }

    /**
     * Methode qui permet au membre de disliker un touit
     * @param Touit $t
     * @return void
     */
    public function dislike(Touit $t) : void {

    }

    /**
     * Methode pour permettre au membre de suivre un tag
     * @param Tag $t
     * @return void
     */
    public function suivreTag(Tag $t) :void {

    }

    /**
     * Methode pour récuperer la listes des touits d'un utilisateur donné
     * @param User $user
     * @return array liste des touits d'un utilisateur donné
     */
    public function getTouitUser(User $user): array {
        $res = [];
        return $res ;
    }

    /**
     * Methode pour récuperer la liste des touits d'un tag donné
     * @param Tag $tag
     * @return array liste des touits d'un tag donné
     */
    public function getTag(Tag $tag) : array {
        $res = [];
        return $res;
    }

    /**
     * @return array
     */
    public function getTouitPubliés(): array {
        return $this->touitPubliés;
    }

    /**
     * @return int
     */
    public function getRole(): int {
        return $this->role;
    }
}