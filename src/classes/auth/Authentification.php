<?php

namespace iutnc\touiteur\auth;

use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\exceptions\AuthException;
use iutnc\touiteur\touit\User;
use PDO;
use function Symfony\Component\String\u;

class Authentification
{
    /**
     * Methode qui permet de se connecter au site wev
     *
     * @param string $email : email de l'utilisateur
     * @param string $password : mot de passe de l'utilisateur
     * @return void
     * @throws AuthException : si le mot de passe est incorrect
     */
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

    /**
     * Methode qui permet de mettre en session l'utilisateur
     *
     * @param string $email : email de l'utilisateur
     * @return void
     */
    public static function loadProfile(string $email) : void
    {
        $db = ConnectionFactory::makeConnection();
        $requete = $db->prepare("SELECT * FROM USER WHERE email = ?");
        $requete->bindParam(1, $email);
        $requete->execute();
        $infoUser = $requete->fetch(PDO::FETCH_ASSOC);

        $user = new User($infoUser['pseudo'], $infoUser['nom'], $infoUser['email'], $infoUser['role']);
        $_SESSION['user'] = serialize($user);
    }
}