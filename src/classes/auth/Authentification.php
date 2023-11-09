<?php

namespace iutnc\touiteur\auth;

use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\exceptions\AuthException;
use iutnc\touiteur\touit\User;
use PDO;
use function Symfony\Component\String\u;

class Authentification
{
    public static function authenticate(string $email, string $password) : void
    {
        $db = ConnectionFactory::makeConnection();
        $requete = $db->prepare("SELECT passwd FROM USER WHERE email = ?");
        $requete->bindParam(1, $email);
        $requete->execute();

        $hashpass = $requete->fetch(PDO::FETCH_ASSOC);
        if (!password_verify($password, $hashpass['passwd'])) {
            throw new AuthException("Problème lors de la connection au compte");
        } else {
            Authentification::loadProfile($email);
        }
    }

    public static function loadProfile(string $email) : void
    {
        $db = ConnectionFactory::makeConnection();
        $requete = $db->prepare("SELECT * FROM USER WHERE email = ?");
        $requete->bindParam(1, $email);
        $requete->execute();
        $infoUser = $requete->fetch(PDO::FETCH_ASSOC);

        $user = new User($infoUser['id'] ,$infoUser['pseudo'], $infoUser['nom'], $infoUser['prenom'], $infoUser['email'], $infoUser['role']);
        $_SESSION['user'] = serialize($user);
    }
}