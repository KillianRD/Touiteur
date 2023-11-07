<?php

namespace iutnc\deefy\dispatch;


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
            case 'add-playlist' :
                $a = new AddPlaylist();
                $html = $a->execute();
                break;
            case 'add-podcasttrack' :
                $a = new AddPodcastTrack();
                $html = $a->execute();
                break;
            case 'signin' :
                $a = new SigninAction();
                $html = $a->execute();
                break;
            case 'display-playlist' :
                $a = new DisplayPlaylistAction();
                $html = $a->execute();
                break;
            default :
                $html = <<<END
                    Bienvenue sur le site de SpotiBuz!
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
                <link rel="shortcut icon" href="./images/play-circle.svg" type="image/x-icon">
                <link rel="stylesheet" href="./style/stylesheet.css">
                <title>Spotibuz</title>
            </head>
            <body>
                <h1>SpotiBuz</h1>
                <nav><ul>
                    <img src="./images/house.svg" alt="Maison representant l'accueil"/><a href='.'>Accueil</a><br>
                    <img src="./images/person-add.svg" alt="Personne representant un ajout de compte"/><a href='?action=add-user'>Inscription</a><br>
                    <img src="./images/person.svg" alt="Personne representant un compte deja existant"/><a href='?action=signin'>Se connecter</a><br>
                    <img src="./images/plus-circle.svg" alt="Plus representant le l'ajout d'une playlist"/><a href='?action=add-playlist'>Creer une playlist</a><br>
                    <img src="./images/card-list.svg" alt="List reprensentant un playlist"/><a href='?action=display-playlist'>Display</a><br>
                </ul></nav>
                $html
            </body>
            </html>
        END;
    }
}