<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends abstractController
{
public function  index(){

    return $this->render('users/index.html.twig', [
        'controller_name' => 'MainController',
    ]);
}
}