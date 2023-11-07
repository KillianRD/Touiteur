<?php

namespace iutnc\touiteur\render;

use iutnc\touiteur\render\Renderer;
use iutnc\touiteur\touit\Tag;
require_once 'vendor/autoload.php';
class TagRender implements Renderer
{
    public string $http;
    private Tag $tag;
}