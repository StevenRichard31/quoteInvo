<?php

namespace AppBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{
    //on donne comme parent FOSUserBundle à AppBundle
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
