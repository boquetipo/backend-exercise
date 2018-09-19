<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Service\Recipe;

class RecipeController extends AbstractController
{
    /**
     * @Route("/", name="recipe")
     */
    public function index()
    {
        $search = null;
        $ingredients = null;

        if (isset($_GET['q'])) {
            $search = $_GET['q'];
        }

        if (isset($_GET['i'])) {
            $ingredients = str_replace(' ', ',', urldecode($_GET['i']));
        }

        if (isset($_GET['p']) && intval($_GET['p']) > 0) {
            $page = intval($_GET['p']);
        } else {
            $page = 1;
        }

        $recipe = new Recipe();
        $recipes = $recipe->get($search, $ingredients, $page);

        if ('' != $recipes) {
            $res = $recipe->get_list($recipes);
            $recipes = $res->results;
        } else {
            $recipes = [];
        }

        $search = ['bbq', 'paella'];

        return $this->render('recipe/index.html.twig', [
            'controller_name' => 'RecipeController',
            'search' => $search,
            'recipes' => $recipes,
            'page' => $page,
        ]);
    }
}
