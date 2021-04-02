<?php


namespace App\Controller;


use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Form\BookType;
use App\Form\SearchBookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use App\Service\callApiService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Json;


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


    public function getBook(callApiService $callApiService, string $id, Request $request, AuthorRepository $authorRepository, CategoryRepository $categoryRepository): Response
    {

        $googleBooksID = $id;
        $googleBook = json_decode($callApiService->getBook($googleBooksID));
        $book = new Book();
        $arrayBook = [];
        $arrayAuthors = [];
        $arrayCategory = [];
        array_push($arrayBook, $googleBook);

/*dd($googleBook);*/
        /*Pour récupérer les auteurs*/
        foreach ($arrayBook as $value) {
            array_push($arrayAuthors, $value->volumeInfo->authors);
        }


        /*Pour récupérer les catégories*/
        foreach ($arrayBook as $value) {
            array_push($arrayCategory, $value->volumeInfo->categories);
        }
        /*Gestion de l'ajout des auteurs et des catégories*/
        $entityManager = $this->getDoctrine()->getManager();
        $authors = [];
        $authors[0] = $authorRepository->findOneBy(['name' => $arrayAuthors[0]]);
        $category = [];
        $category[0] = $categoryRepository->findOneBy(['name' => $arrayCategory[0]]);


        if ($authors[0] && $category[0]) {
            $book->addAuthor($authors[0]);
            $book->addCategory($category[0]);
        } else {
            $author = new Author();
            $category = new Category();
            $author->setName($arrayAuthors[0][0]);
            $category->setName($arrayCategory[0][0]);
            $entityManager->persist($author);
            $entityManager->persist($category);
            $entityManager->flush();
            $resultAuthor = $authorRepository->findOneBy([], ['id' => 'DESC']);
            $resultCategory = $categoryRepository->findOneBy([], ['id' => 'DESC']);
            $book->addAuthor($resultAuthor);
            $book->addCategory($resultCategory);
            /*dd($authors);*/
        }
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        /*dd($form);*/

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($book);
            $entityManager->flush();
            return $this->redirectToRoute('index');
        }
        /*On insère dans les champs les valeurs de google par défaut du livre*/
        $form->get('name')->setData($googleBook->volumeInfo->title);
        $form->get('summary')->setData(strip_tags($googleBook->volumeInfo->description));
        $form->get('publication')->setData($googleBook->volumeInfo->publishedDate);
        $form->get('cover')->setData($googleBook->volumeInfo->imageLinks->thumbnail);

        return $this->render('admin/book.html.twig', [
            'googleBook' => $googleBook,
            'book' => $book,
            'form' => $form->createView(),
        ]);

    }

public function booksList(BookRepository $bookRepository){

        $books = $bookRepository->findAll();

    return $this->render('/admin/book/index.html.twig', [
        'books' => $books
    ]);
}

public function show(){

         return $this->render('/admin/book/show.html.twig');
}

public function edit(){

       return $this->render('/admin/book/edit.html.twig');
}
}