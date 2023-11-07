<?php

namespace iutnc\touiteur\touit;

use iutnc\touiteur\exeptions\InvalideTouitException;
use iutnc\touiteur\exeptions\InvalidPropertyNameException;

require_once 'vendor/autoload.php';

class Touit
{
    /**
     * @var string $texte : Texte d'un touit
     */
    private string $texte;
    /**
     * @var string $pseudo : Pseudo de l'auteur du touit
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
     * @param string $text
     * @param string $pseudo
     * @param string $date
     * @param string $image
     * @throws InvalideTouitException
     * Constructeur de la classe Touit qui permet de créer un touit qui prend en paramètre
     * un texte, le pseudo de l'auteur du touit, la date de publication du touit et une possible image
     */

    public function __construct(string $text, string $pseudo, string $date, string $image='')
    {

        if (strlen($text) > 235) {
            throw new InvalideTouitException("Le touit est trop long");
        }else {
            $this->texte = $text;
        }
        $this->pseudo = $pseudo;
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

    /**
     * @param int $note
     *
     * Methode qui permet d'ajouter un like au touit
     */
    public function setNote(int $note): void
    {
        $this->note = $note;
    }

}