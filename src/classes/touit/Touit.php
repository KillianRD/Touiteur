<?php

namespace iutnc\touiteur\touit;

use iutnc\touiteur\exeptions\InvalideTouitException;
use iutnc\touiteur\exeptions\InvalidPropertyNameException;

require_once 'vendor/autoload.php';

class Touit
{
    private string $texte;
    private string $pseudo;
    private string $date;
    private int $note;
    private ?array $nbTags;
    private ?string $image;

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

    public function __get(string $at): mixed {
        if (property_exists($this, $at)) {
            return $this->$at;
        }
        throw new InvalidPropertyNameException("$at: propriété inconnue");
    }

    /**
     * @param int $note
     */
    public function setNote(int $note): void
    {
        $this->note = $note;
    }

}