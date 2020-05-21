<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\Exception\NoSuchOptionException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class WildController
 * @package App\Controller
 *
 * @route("/wild", name="wild_")
 *
 */
class WildController extends AbstractController
{

    /**
     * @return Response
     *
     * @route("/", name="index")
     */
    public function index() : Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }

        return $this->render(
            'wild/index.html.twig',
            ['programs' => $programs]
        );
    }

    /**
     * @param $title
     * @return Response
     *
     * @route("/show/{slug<[a-z0-9-]+>}", name="show")
     */
    public function show(?string $slug=null) : Response
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }

        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );

        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with '.$slug.' title, found in program\'s table.'
            );
        }

        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug'  => $slug,
        ]);
    }

    /**
     * @param $title
     * @return Response
     *
     * @route("/category/{categoryName<[a-z0-9-]+>}", name="showCategory")
     */
    public function showByCategory(string $categoryName) : Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => mb_strtolower($categoryName)]);

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' => $category],['id'=>'desc'],3,0);

        return $this->render(
            'wild/category.html.twig',
            ['category' => $category, 'programs' => $programs]
        );

    }

}