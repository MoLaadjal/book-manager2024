<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
        
    }

    #[Route('/book', name: 'app_book')]
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }

    

    #[Route('/book/{id}/show', name: 'app_book_show')]
    public function show(string $id, BookRepository $bookRepository): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $bookRepository->find($id),
        ]);
    }

    #[Route('/book/{id}/edit', name: 'app_book_edit')]
    public function edit(
        Book $book,
        Request $request,
    ): Response {
        $form = $this->createForm(BookType::class, $book);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
        }

        return $this->render('book/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/book/add', name: 'app_book_add')]
    public function add(
        Request $request,
    ): Response {
        $book = new Book();

        $form = $this->createForm(BookType::class, $book);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($book);
            $this->em->flush();
        }

        return $this->render('book/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/book/{id}/delete', name: 'app_book_delete')]
    public function delete(
        Book $book,
    ): Response {
        $this->em->remove($book);
        $this->em->flush();

        return $this->redirectToRoute('app_book');
    }
}