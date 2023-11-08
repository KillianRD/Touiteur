<?php

namespace iutnc\touiteur\lists;

require_once 'vendor/autoload.php';

use iutnc\touiteur\exceptions\InvalidArgumentException;
use iutnc\touiteur\exceptions\TagInexistantException;
use iutnc\touiteur\exceptions\InvalidPropertyNameException;
use iutnc\touiteur\touit\Tag;

class ListTags
{
    /**
     * @var array $listTags : Liste des tags
     */
    private array $listTags = [];

    /**
     * @var int $nbTags : Nombre de tags
     */
    private int $nbTags;

    /**
     * @param array $listTags : Liste des tags
     * @throws InvalidArgumentException
     */
    public function __construct(array $listTags =[])
    {
        if (!empty($listTags)) {
            foreach ($listTags as $tag) {
                if ($tag instanceof Tag) {
                    throw new InvalidArgumentException("Le tag n'est pas valide");
                }
            }
        }
        $this->listTags = $listTags;
        $this->nbTags = count($listTags);
    }

    /**
     * Methode qui permet d'ajouter un tag à la liste des tags
     *
     * @param Tag $tag
     * @return void
     */
    public function ajoutTag(Tag $tag): void
    {
        array_push($this->listTags, $tag);

    }

    /**
     * Methode qui permet de supprimer un tag de la liste des tags
     *
     * @param Tag $tag : Tag à supprimer
     * @return void
     * @throws TagInexistantException
     */
    public function suppTag(Tag $tag): void
    {
        $index = array_search($tag, $this->listTags);
        if ($index !== false) {
            unset($this->listTags[$index]);

        } else {
            throw new TagInexistantException("Le tag n'existe pas");
        }
    }

    /**
     * Methode pour récupérer les propriétés de la classe
     *
     * @param string $at : Nom de la propriété
     * @return mixed : Valeur de la propriété
     * @throws InvalidPropertyNameException
     */
    public function __get(string $at): mixed {
        if (property_exists($this, $at)) {
            return $this->$at;
        }
        throw new InvalidPropertyNameException("$at: propriété inconnue");
    }



}