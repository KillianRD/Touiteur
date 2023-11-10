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
        if(isset($_SESSION['user'])){
            $u = unserialize($_SESSION['user']);
            if($this->touit->pseudo === $u->pseudo){
                return "<a href='?action=supprimerTouit&id={$this->touit->id}'>Supprimer Touit</a>" .
                    "<p>{$this->touit->texte}</p>" .
                    "<a href='?action=TouitDetail&id={$this->touit->id}'>+</a>";
            }
        }
        return "<p>{$this->touit->texte}</p>" .
                "<a href='?action=TouitDetail&id={$this->touit->id}'>+</a>";
    }

    public function long(): string
    {
        $id = Touit::getIdUserByIdTouit($this->touit->id);
        return "<a href='?action=otherprofil&id={$id}'>@"."{$this->touit->pseudo}</a>" .
            "<p>{$this->touit->date}</p>" .
            "<p>{$this->touit->texte}</p>" .
            "<p>{$this->touit->note}</p>" .
            "<a href='?action=liker&id={$this->touit->id}'>+</a>" .
            "<a href='?action=disliker&id={$this->touit->id}'>-</a>";
    }
}