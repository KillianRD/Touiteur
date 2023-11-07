<?php

namespace iutnc\touiteur\lists;

require_once 'vendor/autoload.php';

use iutnc\touiteur\exceptions\InvalidArgumentException;
use iutnc\touiteur\exceptions\TagInexistantException;
use iutnc\touiteur\exceptions\InvalidPropertyNameException;
use iutnc\touiteur\touit\Tag;

class ListTags
{
    private array $listTags = [];
    private int $nbTags;

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

    public function ajoutTag(Tag $tag): void
    {
        array_push($this->listTags, $tag);

    }

    public function suppTag(Tag $tag): void
    {
        $index = array_search($tag, $this->listTags);
        if ($index !== false) {
            unset($this->listTags[$index]);

        } else {
            throw new TagInexistantException("Le tag n'existe pas");
        }
    }

    public function __get(string $at): mixed {
        if (property_exists($this, $at)) {
            return $this->$at;
        }
        throw new InvalidPropertyNameException("$at: propriété inconnue");
    }



}