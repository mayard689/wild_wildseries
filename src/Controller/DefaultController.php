<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package App\Controller
 *

 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index() :Response
    {
        return $this->render('home.html.twig');
    }
}
