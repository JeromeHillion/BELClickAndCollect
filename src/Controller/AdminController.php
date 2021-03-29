<?php


namespace App\Controller;


use App\Entity\Book;
use App\Form\BookType;
use App\Form\SearchBookType;
use App\Service\callApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;



class AdminController extends abstractController
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

    public function getBook(callApiService $callApiService, string $id, Request $request): Response
    {
        $googleBooksID = $id;
        $googleBook = $callApiService->getBook($googleBooksID);
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();
            return $this->redirectToRoute('book_index');
        }
            return $this->render('admin/book.html.twig', [
                'googleBook' => json_decode($googleBook),
                'book' => $book,
                'form' => $form->createView(),
            ]);

        }


}