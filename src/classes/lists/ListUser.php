<?php

namespace iutnc\touiteur\lists;

use iutnc\touiteur\exceptions\InvalidArgumentException;
use iutnc\touiteur\exceptions\InvalidPropertyNameException;
use iutnc\touiteur\exceptions\UserInexistantException;
use iutnc\touiteur\touit\User;

class ListUser {
    private int $nbUser;
    private array $users =[];

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
    public function add(User $u){
        array_push($this->users, $u);
        $this->nbUsers++;
    }

    public function suppr(User $u) {
        $index = array_search($u, $this->users);
        if ($index !== false) {
            unset($this->users[$index]);
        } else {
            throw new UserInexistantException("L'user n'existe pas");
        }
    }

    public function __get(string $at):mixed {
        if (property_exists($this,$at)) return $this->$at;
        throw new InvalidPropertyNameException(get_called_class()." attribut invalid". $at);
    }

}