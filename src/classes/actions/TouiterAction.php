<?php

namespace iutnc\touiteur\actions;

use iutnc\deefy\action\Actions;
use iutnc\touiteur\exceptions\InvalideTouitException;
use iutnc\touiteur\render\TouitRender;
use iutnc\touiteur\touit\Touit;

class TouiterAction extends Actions
{

    /**
     * @throws InvalideTouitException
     */
    public function execute(): string
    {
        $html = '';
        if ($this->http_method === 'GET') {
            return <<<END
                <form method='post' action='?action=touiter' enctype='multipart/form-data'>
                <input type='text' placeholder='Quoi de neuf' name='touit'><br><br>
                <input type='file' name='image'><br><br>
                <button type='submit' name='valider' value='validation-form-touiter'>Touiter</button>
                </form>
            END;
        } else {
            $RepertoireUpload = "./image/";
            $nomFichier = uniqid();
            $tmp = $_FILES['image']['tmp_name'];

            if (($_FILES['image']['error'] === UPLOAD_ERR_OK) && ($_FILES['image']['type'] === 'image/png')) {
                $dest = $RepertoireUpload . $nomFichier . '.png';
                if (move_uploaded_file($tmp, $dest)) {
                    $touitText = filter_var($_POST['touit'], FILTER_SANITIZE_STRING);

                    $u = unserialize($_SESSION['user']);
                    $touit = new Touit($touitText, $u, date("d-m-Y H:i"), $dest);;
                    $u->publierTouit($touitText, $dest);
                    $_SESSION = serialize($u);

                    $render = new TouitRender($touit);
                    $html .= $render->render();
                    $html .= "<a href='?action=add-podcasttrack'>Faire un nouveau Touit</a><br>";
                } else {
                    $html = "telechargment non valide<br>";
                }
            } else {
                $html = "echec du téléchargement ou type de fichier incorrect <br>";
            }
        }
        return $html;
    }
}