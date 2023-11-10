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