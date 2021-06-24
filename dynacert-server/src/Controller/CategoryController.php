<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/category", name="showCategory")
     */
    public function index(): Response
    {
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $category = $this->getDoctrine()->getRepository(Category::class);
        return $this->json([
            'response' => $category->getCategories(),
        ]);
    }
    /**
     * @Route("/admin/category/update/",methods={"PUT"}, name="updateCategory")
     */
    public function update(Request $request)
    {
        $parametersAsArray = [];
        $content=null;
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);
        }
        $category = $this->getDoctrine()->getRepository(Category::class);
        $category->updateCategory($parametersAsArray['id'],$parametersAsArray['name']);
        return $this->json([
            'response' => $category->getCategories(),

        ]);

    }
    /**
     * @Route("/admin/category/insert/",methods={"POST"}, name="insertCategory")
     */
    public function insert(Request $request)
    {
        $parametersAsArray = [];
        $content=null;
        if ($content = $request->getContent()) {
            $parametersAsArray = json_decode($content, true);
        }
        $category = $this->getDoctrine()->getRepository(Category::class);
        $category->insertCategory($parametersAsArray['category']);
        return $this->json([
            'response' => $category->getCategories(),

        ]);

    }
    /**
     * @Route("/admin/category/delete/{id}",methods={"DELETE"}, name="deleteCategory")
     */
    public function delete($id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class);
        $category->deleteCategory($id);
        return $this->json([
            'response' => $category->getCategories(),
        ]);
    }
}
