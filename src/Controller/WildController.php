<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class WildController
 * @package App\Controller
 *
 * @route("wild", name="_wild")
 */
class WildController extends AbstractController
{

    /**
     * @param $title
     * @return Response
     *
     * @route("/{slug<[a-z0-9-]+>}", name="wild_show")
     */
    public function show(string $slug="Aucune série sélectionnée, veuillez choisir une série")
    {
        $slug=str_ireplace('-', ' ', $slug);
        $slug=ucwords($slug);
        return $this->render('wild/show.html.twig', ['message'=>$slug]);
    }

}