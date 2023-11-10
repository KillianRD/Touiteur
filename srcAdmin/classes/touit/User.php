<?php

namespace iutnc\touiteur\admin\touit;
require_once 'vendor/autoload.php';


use iutnc\touiteur\admin\db\ConnectionFactory;
use iutnc\touiteur\admin\exceptions\InvalidPropertyNameException;
use PDO;

class User
{

    private string $pseudo;
    private string $nom;
    private string $prenom;
    private string $email;
    protected int $role; //role de l'utilisateur
    private string $id;
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

    public static function listInfluenceur(): string
    {
        $db = ConnectionFactory::makeConnection();
        $requete = $db->prepare("SELECT u.pseudo, COUNT(DISTINCT a.id_user1) AS nombre_abonnes
                                FROM user u
                                LEFT JOIN abonnement a ON u.id = a.id_user2
                                GROUP BY u.id, u.pseudo
                                ORDER BY nombre_abonnes ASC");
        $requete->execute();
        $t = '';
        foreach ($requete->fetchAll(PDO::FETCH_ASSOC) as $row){
            $t .= "<p>Pseudo : " . $row["pseudo"] . ", nombre d'abonnées : " . $row['nombre_abonnes'] . "</p>";
        }
        return $t;
    }
}