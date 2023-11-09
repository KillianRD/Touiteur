<?php

namespace iutnc\touiteur\dispatch;


use iutnc\touiteur\actions\AbonneAction;
use iutnc\touiteur\actions\AbonnementAction;
use iutnc\touiteur\actions\AddUserAction;
use iutnc\touiteur\actions\DesabonnerAction;
use iutnc\touiteur\actions\HomeAction;
use iutnc\touiteur\actions\LogoutAction;
use iutnc\touiteur\actions\OtherProfilAction;
use iutnc\touiteur\actions\ProfilAction;
use iutnc\touiteur\actions\SigninAction;
use iutnc\touiteur\actions\SuivreAction;
use iutnc\touiteur\actions\SuivreTagAction;
use iutnc\touiteur\actions\TagAction;
use iutnc\touiteur\actions\TouitDetailAction;
use iutnc\touiteur\actions\TouiterAction;
use iutnc\touiteur\exceptions\InvalideTouitException;

class Dispatcher
{
    private ?string $action;

    public function __construct()
    {
        $this->action = $_GET['action'] ?? null;
    }

    /**
     * @throws InvalideTouitException
     */
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
            case 'otherprofil' :
                $a = new OtherProfilAction();
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
            case 'touiter' :
                $a = new TouiterAction();
                $html = $a->execute();
                break;
            case 'SuivreTag':
                $a = new SuivreTagAction();
                $html = $a->execute();
                break;
            case 'TouitDetail' :
                $a = new TouitDetailAction();
                $html = $a->execute();
                break;
            case 'desabonner' :
                $a = new DesabonnerAction();
                $html = $a->execute();
                break;
            case 'suivre' :
                $a = new SuivreAction();
                $html = $a->execute();
                break;
            default :
                $html = <<<END
                   <h1>Bienvenue sur Touiteur</h1>
                END;
                break;
        }
        $this->renderPage($html);
    }

    private function renderPage(string $html) : void {
        echo <<<END
            <!DOCTYPE html>
            <html lang='fr'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <link rel='shortcut icon' href='./images/oiseau.png' type='image/x-icon'>
                <link rel='stylesheet' type='text/css' href='./css/home.css'>
                <title>Touiteur</title>
            </head>
            <body> 
                <header>
                    <div>
                        <a href="?action=home" class="home"><img src="./images/oiseau.png" alt="Logo Touiteur" class="logo_touiteur">Touiteur</a>
                    </div>      
                    <nav>
                        <ul>
                            <a href="?action=profil" class="profil"><img src="./images/profil.png" alt="Personne representant un profil" class="logo_profil">Profil</a>
                            <a href="?action=tag" class="tag"><img src="./images/hash.png" alt="Hashtag representant les hashtags" class="logo_hash">Tags</a>            
                            <a href="?action=touiter" class="touiter"><img src="./images/touiter.png" alt="message" class="logo_touiter">Touiter</a>              
                        </ul>
                    </nav>
                </header>

                <main class="content">
                    $html
                </main>      
            </body>
            </html>
END;
    }
}