<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\FortuneCookieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FortuneController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(Request $request, CategoryRepository $categoryRepository): Response
    {
        // $categories = $categoryRepository->findAll(); 

        if ( $request->query->has('q') && $request->query->get('q') !== '') { 
            $categories = $categoryRepository->searchAll($request->query->get('q'));
        }
        else { 
            $categories = $categoryRepository->findAllOrdered(); 
        }


        return $this->render('fortune/homepage.html.twig',[
            'categories' => $categories
        ]);
    }

    #[Route('/category/{id}', name: 'app_category_show')]
    public function showCategory(Category $category, FortuneCookieRepository $fortuneCookieRepository): Response
    {

        $rawResult = $fortuneCookieRepository->rawQuery(); 
        // dump($rawResult); 

        $inProductionCookieList = $fortuneCookieRepository->inProductionCookies(); 

        return $this->render('fortune/showCategory.html.twig',[
            'category' => $category, 
            'inProductionCookieList' => $inProductionCookieList, 
        ]);
    }
}
