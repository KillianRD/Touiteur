<?php

namespace iutnc\touiteur\admin\actions;

use iutnc\touiteur\admin\touit\Tag;
class TendanceAction extends Actions
{

    public function execute(): string
    {
        return Tag::ListTag();
    }
}