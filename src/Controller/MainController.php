<?php


namespace App\Controller;


use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Form\BookType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


class MainController extends abstractController
{
public function  index(){

    return $this->render('users/index.html.twig');
}
public function  books(BookRepository  $bookRepository){
    $books = $bookRepository->findAll();


    return $this->render('users/books.html.twig', [
        'books' => $books,

    ]);
}

    public function book(int $id ,BookRepository $bookRepository):Response
    {
        $this->getDoctrine()
        ->getRepository(Book::class)
        ->find($id);
        $book = json_encode($bookRepository->find($id));
        return new Response($book);



    }
}