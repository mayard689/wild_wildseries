<?php


namespace App\Controller;


use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    /**
     * @Route("/add", name="add_category")
     */
    public function add(Request $request, CategoryRepository $categoryRepository) :Response
    {

        $category=new Category();

        $form = $this->createForm(
            CategoryType::class,
            $category,
            ['method' => Request::METHOD_GET]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $category = $form->getData();
            var_dump($category);
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($category);
             $entityManager->flush();

            return $this->redirectToRoute('add_category');
        }

        $categories=$categoryRepository->findAll();

        return $this->render('admin/category.html.twig',[
            'form' => $form->createView(),
            'categories' => $categories
        ]);
    }


}