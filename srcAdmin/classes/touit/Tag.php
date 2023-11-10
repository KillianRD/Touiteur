<?php

namespace iutnc\touiteur\admin\touit;

use iutnc\touiteur\exceptions\InvalidPropertyNameException;
use PDO;

class Tag
{
    private string $nom;

    public function __construct(string $n)
    {
        $this->nom = $n;
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