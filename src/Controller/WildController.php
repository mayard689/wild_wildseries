<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use Doctrine\Common\Collections\Collection;
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
        } else {
            //note that this line is here as requested by the quest review list but useless as twig xan acce the season directly from the program object
            $seasons=$program->getSeasons();
        }

        return $this->render('wild/show.html.twig', [
            'program' => $program,
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

    /**
     * @param $seasonId
     * @return Response
     *
     * @route("/season/{seasonId<[0-9]+>}", name="showSeason")
     */
    public function showBySeason(string $seasonId) : Response
    {
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['id' => mb_strtolower($seasonId)]);

        return $this->render(
            'wild/season.html.twig',
            ['season' => $season]
        );

    }

    /**
     * @param $seasonId
     * @return Response
     *
     * @route("/season2/{id<[0-9]+>}", name="showSeason2")
     */
    public function showBySeason2(int $id): Response
    {
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['id' => $id]);
        if (!$id) {
            throw $this->createNotFoundException(
                'No season with ' .$id.' identifier.'
            );
        }
        $program = $season->getProgram();
        $slug = preg_replace(
            '/ /',
            '-', strtolower($program->getTitle())
        );

        return $this->render('Wild/showBySeason.html.twig', [
            'season' => $season,
            'slug' => $slug
        ]);
    }

    /**
     * @param $seasonId
     * @return Response
     *
     * @route("/episode/{id<[0-9]+>}", name="showEpisode")
     */
    public function showEpisode(Episode $episode) : Response
    {
        $season=$episode->getSeason();
        $program=$season->getProgram();

        return $this->render(
            'wild/episode.html.twig',
            ['episode' => $episode]
        );

    }
}