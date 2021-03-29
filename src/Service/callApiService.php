<?php


namespace App\Service;


use Symfony\Contracts\HttpClient\HttpClientInterface;

class callApiService
{
    private $client;

    /**
     * callApiService constructor.
     * @param $client
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getBookData(string $book)
    {
        $response = $this->client->request(
            'GET',
            'https://www.googleapis.com/books/v1/volumes?q=' . $book . '&key=AIzaSyD8AeRy2SQTAQcjPAcscSQUx6H2Jlzmo6w'
        );

        return $response->getContent();
    }

    public function getBook(string $id)
    {
        $response = $this->client->request(
            'GET',
            'https://www.googleapis.com/books/v1/volumes/'. $id . '?key=AIzaSyD8AeRy2SQTAQcjPAcscSQUx6H2Jlzmo6w'
        );
        return $response->getContent();
    }
}