<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeController extends AbstractController
{
    #[Route('/recipes', name: 'recipe.index')]
    public function index(Request $request, RecipeRepository $repository, EntityManagerInterface $em): Response
    {
        $recipes = $repository->findAll();  // ici on recupere toutes les recettes
        //$recipes = $repository->findWithDurationLowerThan(60);  // ici on recupere 1 recette de moins de 60 miniutes avec la fonction qu'on a créé dans le RecipeRepository

        //$recipes[0]->setName('Tartelettes au citron meuringué'); // ici on cherche à modifier le titre de ma premiere recette 
        //$em -> flush();

        //créataion d'une nouvelle recette
        // $recipe = new Recipe ;
        //  $recipe->setName('Chakhchoukha')
        // ->setSlug('Chakhchoukha')
        // ->setName('Chakhchoukha')
        // ->setDuration(60)
        // ->setAuteur('Dalila')
        // ->setCreatedAt(New \DateTimeImmutable())
        //  ->setDescription('Plat traditionnel de l\'est de l\'Algérie')
        //  ->setNumberPeople(4)
        //  ->setIngredients('Pates de chakhchoukha , tomates, oiognons, piments, beurre, huile, pois chiches, viandes(selon le choix)');
        //   $em->persist($recipe);
        //  $em -> flush();


        // supprimer une recette
        //$em -> remove($recipes[7]);
        // $em -> flush();
        return $this->render('recipe/index.html.twig', ['recipes' => $recipes]);
    }


    #[Route('/recipe/{slug}-{id}', name: 'recipe.show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]  //expressions regulires REGEX
    public function show(Request $request, string $slug, RecipeRepository $repository, int $id): Response
    {
        $recipe = $repository->find($id);
        if ($recipe->getSlug() != $slug) {
            return $this->redirectToRoute('recipe/show', ['slug' => $recipe->getSlug(), 'id' => $recipe->getId()]);
        }
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe
        ]);
        // return  $this->render('recipe/show.html.twig' , [
        // 'slug' => $slug ,
        //'demo' =>'<strong>Hello</strong>',
        // 'id' => $id ,
        //'person' => [
        //    'firstname' => 'John',
        //   'lastname' => 'Doe '
        // ]
        //]);
        // return new Response ('Recette : ' . $slug . ' ' . $id);
        // return new Response ('Recette : ' . $request->attributes->get('slug') . " " .$request->attributes->get('id'));
        // dd($request->attributes->get('slug'),$request->attributes->get('id'));
        //dd($request);
        //return $this->render('recipe/index.html.twig', [
        // 'controller_name' => 'RecipeController',
        //]);
    }

    // Route pour le formulaire 

    #[Route('/recipe/{id}/edit', name: 'recipe.edit')]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Recette modifiée avec succes');
            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form
        ]);
    }

    //Route create

    #[Route('/recipe/create', name: 'recipe.create')]
    public function create(Request $request, EntityManagerInterface $em)
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $recipe->setCreatedAt(New \DateTimeImmutable());
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success', 'Recette ajoutée avec succes');
            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('recipe/create.html.twig', [
            'form' => $form
        ]);
    }

    //route delete
    #[Route('/recipe/{id}/remove', name: 'recipe.remove')]
    public function remove(Recipe $recipe, Request $request, EntityManagerInterface $em)
    {
       $em->remove($recipe);
       $em->flush();
       $this->addFlash('success', 'Recette supprimée avec succes'); 
            return $this->redirectToRoute('recipe.index');
        }

 
}
