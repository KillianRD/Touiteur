<?php

namespace iutnc\touiteur\touit;

use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\exceptions\InvalideTouitException;
use iutnc\touiteur\exceptions\InvalidPropertyNameException;
use iutnc\touiteur\exceptions\TagInexistantException;
use iutnc\touiteur\lists\ListTouit;
use iutnc\touiteur\render\ListTouitRender;
use PDO;

class Tag
{
    private string $nom;

    public function __construct()
    {
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


}