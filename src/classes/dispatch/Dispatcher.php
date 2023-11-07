<?php

<<<<<<< HEAD
namespace iutnc\touiteur\dispatch;
=======
namespace iutnc\touiter\dispatch;
>>>>>>> origin/main


use iutnc\touiteur\actions\AddUserAction;
use iutnc\touiteur\actions\HomeAction;
use iutnc\touiteur\actions\LogoutAction;
use iutnc\touiteur\actions\ProfilAction;
use iutnc\touiteur\actions\SigninAction;
use iutnc\touiteur\actions\TagAction;

class Dispatcher
{
    private ?string $action;

    public function __construct()
    {
        $this->action = $_GET['action'] ?? null;
    }

    public function run(): void
    {
        switch ($this->action) {
            case 'add-user' :
                $a = new AddUserAction();
                $html = $a->execute();
                break;
            case 'signin' :
                $a = new SigninAction();
                $html = $a->execute();
                break;
            case 'logout' :
                $a = new LogoutAction();
                $html = $a->execute();
                break;
            case 'home' :
                $a = new HomeAction();
                $html = $a->execute();
                break;
            case 'tag' :
                $a = new TagAction();
                $html = $a->execute();
                break;
            case 'profil' :
                $a = new ProfilAction();
                $html = $a->execute();
                break;
            case 'abonne' :
                $a = new AbonneAction();
                $html = $a->execute();
                break;
            case 'abonnement' :
                $a = new AbonnementAction();
                $html = $a->execute();
                break;
            default :
                $html = <<<END
                   <h1>Bienvenu sur Touiteur</h1>
                END;
                break;
        }
        $this->renderPage($html);
    }

    private function renderPage(string $html) : void
    {
        echo <<<END
            <!DOCTYPE html>
            <html lang='fr'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <link rel='shortcut icon' href='./images/play-circle.svg' type='image/x-icon'>
                <link rel='stylesheet' type='text/css' href='./css/style.css'>
                <title>Touiter</title>
            </head>
            <body>
                <img src="./images/touiter" alt="Logo reprensentant Touiter"><a href='?action'></a></img>
                <nav><ul>
                    <a href='?action=home'>Accueil<img src="./images/" alt="Maison representant l'accueil"/></a><br>
                    <a href='?action=tag'>Voir un tag<img src="./images/" alt="Hastag representant les hastags"/></a><br>
                    <a href='?action=profil'>Profil<img src="./images/" alt="Personne representant un profil"/></a><br>
                    <a href='?action=signin'>Se connecter<img src="./images/" alt="Personne representant un compte deja existant"/></a><br>
                    <a href='?action=add-user'>Inscription<img src="./images/" alt="Personne representant un ajout de compte"/></a><br>         
                    <a href='?action=touiter'>Touiter<img src="./images/" alt="Mail representant la creation d'un touite"/></a><br>         
                    <a href='?action=logout'>Se deconnecter</a><br>
                </ul></nav>
                $html
            </body>
            </html>
        END;
    }
}