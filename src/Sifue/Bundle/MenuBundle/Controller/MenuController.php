<?php

namespace Sifue\Bundle\MenuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MenuController extends Controller
{
    public function indexAction()
    {
        return $this->render('SifueMenuBundle:Menu:menu.html.twig', array());
    }
}
