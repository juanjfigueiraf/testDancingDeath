<?php

namespace DWD\TestAsimovBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DWDTestAsimovBundle:Default:index.html.twig', array('name' => $name));
    }
}
