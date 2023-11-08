<?php

namespace iutnc\touiteur\lists;

use iutnc\touiteur\exceptions\InvalidArgumentException;
use iutnc\touiteur\exceptions\InvalidPropertyNameException;
use iutnc\touiteur\exceptions\UserInexistantException;
use iutnc\touiteur\touit\User;

class ListUser {
    /**
     * @var int $nbUser : Nombre d'user
     */
    private int $nbUser;

    /**
     * @var array $users : Liste des users
     */
    private array $users =[];

    /**
     *
     * @param array $users : Liste des users
     * @throws InvalidArgumentException : Si la liste ne contient pas que des users
     */
    public function __construct(array $users =[]) {
        if(!empty($users)) {
            foreach ($users as $user) {
                if(!$user instanceof User) {
                    throw new InvalidArgumentException("La liste ne doit contenir que des users");
                }
            }
            $this->users = $users;
            $this->nbUser = count($users);
        }
    }

    /**
     * Methode qui permet d'ajouter un user à la liste des users
     *
     * @param User $u : User à ajouter
     * @return void
     */
    public function add(User $u){
        array_push($this->users, $u);
        $this->nbUsers++;
    }


    /**
     * Methode qui permet de supprimer un user de la liste des users
     *
     * @param User $u : User à supprimer
     * @return void
     * @throws UserInexistantException : Si l'user n'existe pas
     */
    public function suppr(User $u) {
        $index = array_search($u, $this->users);
        if ($index !== false) {
            unset($this->users[$index]);
        } else {
            throw new UserInexistantException("L'user n'existe pas");
        }
    }

    /**
     * Methode pour récupérer les propriétés de la classe
     *
     * @param string $at : Nom de la propriété
     * @return mixed : Valeur de la propriété
     * @throws InvalidPropertyNameException : Si la propriété n'existe pas
     */
    public function __get(string $at):mixed {
        if (property_exists($this,$at)) return $this->$at;
        throw new InvalidPropertyNameException(get_called_class()." attribut invalid". $at);
    }

}