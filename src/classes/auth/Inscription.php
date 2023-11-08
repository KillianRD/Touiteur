<?php

namespace iutnc\touiteur\auth;

use iutnc\touiteur\db\ConnectionFactory;
use iutnc\touiteur\exceptions\AuthException;
use iutnc\touiteur\touit\User;
use PDO;

class Inscription
{
    /**
     * Methode qui permet de créer un compte
     *
     * @param string $email : email de l'utilisateur
     * @param string $pass : mot de passe de l'utilisateur
     * @param string $confirmPass : confirmation du mot de passe de l'utilisateur
     * @param string $nom : nom de l'utilisateur
     * @param string $prenom : prenom de l'utilisateur
     * @param string $pseudo : pseudo de l'utilisateur
     * @return void
     * @throws AuthException : si l'inscription ne s'est pas bien passée
     */
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

    /**
     * Methode qui permet de vérifier la force du mot de passe
     *
     * @param string $pass : mot de passe de l'utilisateur
     * @param integer $min : taille minimale du mot de passe
     * @return boolean : true si le mot de passe est assez fort, false sinon
     */
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