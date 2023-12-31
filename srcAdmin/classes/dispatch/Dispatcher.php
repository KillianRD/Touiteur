<?php

namespace iutnc\touiteur\admin\dispatch;


use iutnc\touiteur\actions\LogoutAction;
use iutnc\touiteur\admin\actions\InfluenceurAction;
use iutnc\touiteur\admin\actions\SigninAction;
use iutnc\touiteur\admin\actions\TendanceAction;
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
            case 'tendance' :
                $a = new TendanceAction();
                $html = $a->execute();
                break;
            case 'influenceurs' :
                $a = new InfluenceurAction();
                $html = $a->execute();
                break;
            case 'logout' :
                $a = new LogoutAction();
                $html = $a->execute();
                break;
            default :
                $a = new SigninAction();
                $html = $a->execute();
                break;

        }
        $this->renderPage($html);
    }

    private function renderPage(string $html): void
    {
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
                            <a href="?action=influenceurs" class="profil_home"><img src="./images/profil.png" alt="Personne representant un profil" class="logo_profil">Influenceurs</a>
                            <a href="?action=tendance" class="touiter"><img src="./images/touiter.png" alt="message" class="logo_touiter">Tendance</a>              
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