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

}