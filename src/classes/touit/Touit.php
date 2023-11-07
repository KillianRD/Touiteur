<?php

namespace iutnc\touiteur\touit;

use iutn\touiter\db\ConnectionFactory;
use iutnc\touiteur\exceptions\InvalideTouitException;
use iutnc\touiteur\exceptions\InvalidPropertyNameException;

require_once 'vendor/autoload.php';

class Touit
{
    /**
     * @var string $texte : Texte d'un touit
     */
    private string $texte;
    /**
     * @var User $user : Pseudo de l'auteur du touit
     */
    private User $user;
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
     * @param string $text
     * @param User $user
     * @param string $date
     * @param string $image
     * @throws InvalideTouitException
     * Constructeur de la classe Touit qui permet de créer un touit qui prend en paramètre
     * un texte, le pseudo de l'auteur du touit, la date de publication du touit et une possible image
     */

    public function __construct(string $text, User $user, string $date, string $image='')
    {

        if (strlen($text) > 235) {
            throw new InvalideTouitException("Le touit est trop long");
        }else {
            $this->texte = $text;
        }
        $this->user = $user;
        $this->date = $date;
        $this->note = 0;
        $this->nbTags = [];
        $this->image = $image;
    }

    /**
     * @param string $at
     * @return mixed
     * @throws InvalidPropertyNameException
     */
    public function __get(string $at): mixed {
        if (property_exists($this, $at)) {
            return $this->$at;
        }
        throw new InvalidPropertyNameException("$at: propriété inconnue");
    }

    public function __set(string $at, mixed $val = null) {
        if(property_exists($this,$at)) {
            $this->$at = $val;
        } else {
            throw new InvalidPropertyNameException (get_called_class()." attribut invalid". $at);
        }
    }

    /**
     * Methode qui permet de de répertorier tout les touites dans une listes de touites
     * @return array liste de tous les touites
     */
    public function afficherTouites(): array
    {
        $connexion = ConnectionFactory::makeConnection();
        $requete = $connexion->prepare("SELECT text, date, note, description, chemin FROM touite NATURAL JOIN touite2image NATURAL JOIN image");
        $requete->execute();
        foreach ($requete->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $text = $row['text'];
            $date = $row['date'];
            $note = $row['note'];
            $description = $row['description'];
            $chemin = $row['chemin'];
            $t = new Touit($text, $date, $note, $description, $chemin);
            array_push($touites, $t);
        }

        return $touites;
    }

}