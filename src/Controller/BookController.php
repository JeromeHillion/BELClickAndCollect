<?php


namespace App\Controller;


use App\Form\SearchBookType;
use App\Service\callApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;



class BookController extends abstractController
{
    public function index(): Response
    {

        $form = $this->createForm(SearchBookType::class);

        return $this->render('admin/searchBook.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function getBooks(callApiService $callApiService, string $name):Response
    {
        $title = $name;
        $books = $callApiService->getBookData($title);
        /*dd($books);*/
        return new Response($books);
    }

    public function getBook(callApiService $callApiService, string $id)
    {
        $googleBooksID = $id;
        $book = $callApiService->getBook($googleBooksID);
    /*    dd(json_decode($book));*/
        return $this->render('admin/book.html.twig', [
            'book' =>json_decode($book)
        ]);

    }
}