<?php


namespace App\Controller;


use App\Entity\Author;
use App\Entity\Book;
use App\Form\BookType;
use App\Form\SearchBookType;
use App\Repository\AuthorRepository;
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

    public function getBooks(callApiService $callApiService, string $name): Response
    {
        $title = $name;
        $books = $callApiService->getBookData($title);
        /*dd($books);*/
        return new Response($books);
    }

    public function getBook(callApiService $callApiService, string $id, Request $request, AuthorRepository $authorRepository): Response
    {
        /*$authorRepository = new AuthorRepository();*/
        $googleBooksID = $id;
        $googleBook = json_decode($callApiService->getBook($googleBooksID));
        $book = new Book();
        $arrayBook = [];
        $arrayAuthors = [];
        $arrayCategory = [];
        array_push($arrayBook, $googleBook);

        /*Pour récupérer les auteurs*/
        foreach ($arrayBook as $value) {
            array_push($arrayAuthors, $value->volumeInfo->authors);
        }
        /* dd($arrayBook);*/

        /*Pour récupérer les catégories*/
        foreach ($arrayBook as $value) {
            array_push($arrayCategory, $value->volumeInfo->categories);
        }
        /*dd($arrayCategory);*/
        $entityManager = $this->getDoctrine()->getManager();
        $authors = [];
        $authors[0] = $authorRepository->findOneBy(['name' => $arrayAuthors[0]]);
        /*dd($authors[0]);*/
        if ($authors[0]) {
           /* dd($authors);*/
            $book->addAuthor($authors[0]);
        } else {
            $author = new Author();
            /*dd($arrayAuthors);*/
            $author->setName($arrayAuthors[0][0]);
            $entityManager->persist($author);
            $entityManager->flush();
            $result = $authorRepository->findOneBy([], ['id' => 'DESC']);
            $book->addAuthor($result);
            /*dd($authors);*/
        }
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($book);
            $entityManager->flush();
            return $this->redirectToRoute('book_index');
        }
        return $this->render('admin/book.html.twig', [
            'googleBook' => $googleBook,
            'book' => $book,
            'form' => $form->createView(),
        ]);

    }


}