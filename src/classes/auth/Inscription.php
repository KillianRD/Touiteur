<?php

namespace iutnc\touiteur\auth;

use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\exceptions\AuthException;
use iutnc\touiteur\touit\User;
use PDO;

class Inscription
{
    public static function register(string $email, string $pass, string $confirmPass, string $nom, string $prenom, string $pseudo): void
    {
        $db = ConnectionFactory::makeConnection();
        $verifDupEmail = $db->prepare("SELECT email FROM USER WHERE email = ?");
        $verifDupEmail->bindParam(1, $email);
        $verifDupEmail->execute();

        $verifDupPseudo = $db->prepare("SELECT pseudo FROM USER WHERE pseudo = ?");
        $verifDupPseudo->bindParam(1, $email);
        $verifDupPseudo->execute();

        if (($verifDupEmail->fetch(PDO::FETCH_ASSOC) === false) && self::checkPassStrength($pass) && ($pass === $confirmPass) && ($verifDupPseudo->fetch(PDO::FETCH_ASSOC) === false)) {
            $hashpass = password_hash($pass, PASSWORD_DEFAULT, ['cost' => 12]);
            $insert = $db->prepare("INSERT INTO USER (`email`, `passwd`, `nom`, `prenom`, `role`, `pseudo`) VALUES (?, ?, ?, ?, ?, ?)");
            $insert->bindParam(1, $email);
            $insert->bindParam(2, $hashpass);
            $insert->bindParam(3, $nom);
            $insert->bindParam(4, $prenom);
            $insert->bindParam(5, User::$STANDARD_ROLE);
            $insert->bindParam(6, $pseudo);
            $insert->execute();
            Authentification::loadProfile($email);
        } else {
            throw new AuthException("Problème lors de l'inscription");
        }
    }

    private static function checkPassStrength(string $pass, int $min = 8): bool
    {
        $length = (strlen($pass) >= $min);
        $digit = preg_match("#[\d]#", $pass);
        $special = preg_match("#[\W]#", $pass);
        $lower = preg_match("#[a-z]#", $pass);
        $upper = preg_match("#[A-Z]#", $pass);

        if (!$length || !$digit || !$special || !$lower || !$upper) return false;
        return true;
    }
}