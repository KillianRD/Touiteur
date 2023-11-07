<?php

namespace iutnc\touiteur\actions;

class HomeAction extends Actions
{
    public function execute(): string
    {
        $html = '';
        if ($this->http_method === 'GET') {
            return <<<END
                <form method='post' action='?action=home'>
                //A faire
                </form>
            END;
        }

        return $html;
    }
}