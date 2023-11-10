<?php

namespace iutnc\touiteur\render;

use iutnc\touiteur\exceptions\InvalidPropertyNameException;
use iutnc\touiteur\touit\Touit;

require_once 'vendor/autoload.php';

class TouitRender
{
    /**
     * @var Touit $touit : Touit à afficher
     */
    private Touit $touit;

    /**
     * @param Touit $t : Touit à afficher
     */
    public function __construct(Touit $t)
    {
        $this->touit = $t;
    }

    /**
     * Methode pour afficher un touit
     *
     * @return string : Renvoie le touit sous forme de HTML
     */
    public function render(int $selector): string
    {
        switch ($selector) {
            case Renderer::SHORT :
                $html = $this->short();
                break;
            case Renderer::LONG :
                $html = $this->long();
                break;
        }
        return $html;
    }

    public function short(): string
    {
        return <<<END
        <div class="touit">
                <p class="nom">{$this->touit->pseudo}</p>
                <div class="contenu_touit">
                    <p class="texte">{$this->touit->texte}</p>
                </div>        
            <div class="pied">
                <p class="note">Note</p>
                <img src="./images/note.png" alt="note" class="logo_note">       
            </div>
            <a href='?action=TouitDetail&id={$this->touit->id}' class="detail">+</a> 
        </div>\n
END;
    }

    public function long(): string
    {
        $id = Touit::getIdUserByIdTouit($this->touit->id);
        return <<<END
            <div class="touit">
                  <div class="tete">
                    <div class="user">
                      <p class="nom">Mario</p>
                      <a href='?action=otherprofil&id={$id}'>@{$this->touit->pseudo}</a>
                    </div>
                    <p class="date">{$this->touit->date}</p>
                  </div>
                  <div class="contenu">
                    <p class="texte">{$this->touit->texte}</p>
                    <img src="{$this->touit->image}" alt="note" class="logo_test"></a>
                  </div>
                  <div class="pied">        
                      <a href='?action=liker&id={$this->touit->id}'>+</a>
                      <p class="note">{$this->touit->note}</p>
                      <img src="./images/note.png" alt="note" class="logo_note"></a>
                      <a href='?action=disliker&id={$this->touit->id}'>-</a>
                  </div>
            END;
    }
}