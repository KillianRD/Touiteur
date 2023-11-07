<?php

namespace iutnc\touiteur\render;

interface Renderer
{
    public const SHORT = 1;
    public const LONG = 2;

    public function render(int $selector): string;
    public function long();
    public function short();
}