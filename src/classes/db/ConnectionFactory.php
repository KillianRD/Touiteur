<?php

declare(strict_types=1);
namespace iutnc\touiteur\db;

use PDO;

class ConnectionFactory{
    /**
     * @var array : tableau qui contient les informations de la base de donnée
     */
    private static array $InfoDB = [];
    /**
     * @var PDO|null : variable qui contient la connexion à la base de donnée
     */
    public static ?PDO $db = null;

    /**
     * Methode qui permet de lire le fichier config.ini, afin de configurer les informations de la basse de donnée
     * @param String $file : chemin du fichier config.ini
     * @return void
     */
    public static function setConfig(String $file){
        self::$InfoDB = parse_ini_file($file);
    }

    /**
     * Permet de se connecter à la base de donnée
     * @return PDO|null
     */
    public static function makeConnection(): ?PDO
    {
        if(self::$db === null){
            $dsn = self::$InfoDB['driver'] . ":host=" . self::$InfoDB['host'] . ";dbname=" . self::$InfoDB['database'];
            $username = self::$InfoDB['username'];
            $password = self::$InfoDB['password'];

            self::$db = new PDO($dsn, $username,$password,[
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_STRINGIFY_FETCHES => false
            ]);
            self::$db->prepare('SET NAMES\'UTF8\'')->execute();
        }

        return self::$db;
    }
}