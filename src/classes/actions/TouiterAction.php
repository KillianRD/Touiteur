<?php

namespace iutnc\touiteur\actions;

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
                <form method='post' action='?action=touiter' enctype='multipart/form-data' class="nv_touiter">
                    <input type='text' placeholder='Quoi de neuf' name='touit'>
                    <input type='file' name='image'>
                    <button type='submit' name='valider'>Touiter</button>
                </form> 
END;
        } else {
            $touitText = $_POST['touit'];
            if($_FILES['image']['error'] === UPLOAD_ERR_OK){
                $RepertoireUpload = "./image/";
                $nomFichier = uniqid();
                $tmp = $_FILES['image']['tmp_name'];

                if (($_FILES['image']['error'] === UPLOAD_ERR_OK) && ($_FILES['image']['type'] === 'image/png')) {
                    $dest = $RepertoireUpload . $nomFichier . '.png';
                    if (move_uploaded_file($tmp, $dest)) {
                        $u = unserialize($_SESSION['user']);
                        $u->publierTouit($touitText, $dest);
                        $_SESSION = serialize($u);

                        $html .= "<a href='?action=touiter'>Faire un nouveau Touit</a>";
                    } else {
                        $html = "telechargment non valide<br>";
                    }
                } else {
                    $html = "echec du téléchargement ou type de fichier incorrect <br>";
                }
            } else {
                $u = unserialize($_SESSION['user']);
                $u->publierTouit($touitText);
                $_SESSION = serialize($u);
            }
        }
        return $html;
    }
}