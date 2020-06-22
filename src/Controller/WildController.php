<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class WildController
 * @package App\Controller
 *
 * @route("/wild", name="wild_")
 * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
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
     * @route("/show/{slug<[a-z0-9-]{1,}>}", name="show")
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
     * @route("/episode/{id<[0-9]+>}", name="showEpisode")
     */
    public function showEpisode(Episode $episode, ?UserInterface $user) : Response
    {
        $season=$episode->getSeason();

        return $this->render(
            'wild/episode.html.twig',
            ['episode' => $episode,
             'user'=>$user]
        );

    }

    /**
     * @Route("/comment/{id}/delete", name="comment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Comment $comment): Response
    {
        $this->denyAccessUnlessGranted('delete', $comment);

        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('wild_showEpisode',['id'=> $comment->getEpisode()->getId()]);
    }
}
