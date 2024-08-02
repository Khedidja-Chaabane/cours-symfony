<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response 
    { //dd($request);
      //  return new Response ('Bonjour ' . $request->query->get('name' , 'Inconnu'));
 return $this->render('home/index.html.twig');
//return new Response ('Bonjour ' . $_GET['name']);
       // return $this->render('home/index.html.twig', [
          //  'controller_name' => 'HomeController',
       // ]);
    }
}
