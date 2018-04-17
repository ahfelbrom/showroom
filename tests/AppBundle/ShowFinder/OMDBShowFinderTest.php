<?php

namespace Tests\AppBundle\ShowFinder;

use AppBundle\Entity\Category;
use AppBundle\Entity\Show;
use AppBundle\Entity\User;
use AppBundle\ShowFinder\OMDBShowFinder;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class OMDBShowFinderTest extends TestCase
{
    public function testOMDBReturnsNoShows()
    {

        $results = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $results->method('getBody')->willReturn('{"Response":"False", "Error": "Series not found!"}');

        $client = $this
            ->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $client->method('__call')->with($this->equalTo('get'))->willReturn($results);

        $tokenStorage = $this
            ->getMockBuilder(TokenStorage::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $apiKey = '13246489';


        $omdbShowFinder = new OMDBShowFinder($client, $tokenStorage, $apiKey);
        $results = $omdbShowFinder->findByName('test');

        $this->assertSame([], $results);
    }

    public function testOMDBReturnSomeShows()
    {
        $results = $this
            ->getMockBuilder(Response::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $results->method('getBody')->willReturn('{ "Title": "Johnny Test", "Released": "01 Jan 2005", "Genre": "Animation, Action, Adventure", "Country": "USA, Canada", "Poster": "https:\\/\\/ia.media-imdb.com\\/images\\/M\\/MV5BYzc3OGZjYWQtZGFkMy00YTNlLWE5NDYtMTRkNTNjODc2MjllXkEyXkFqcGdeQXVyNjExODE1MDc@._V1_SX300.jpg", "Response": "True"}');

        $client = $this
            ->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $client->method('__call')->with($this->equalTo('get'))->willReturn($results);

        $user = new User();

        $token = $this
            ->getMockBuilder(UsernamePasswordToken::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $token->method('getUser')->willReturn($user);

        $tokenStorage = $this
            ->getMockBuilder(TokenStorage::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $tokenStorage->method('getToken')->willReturn($token);


        $apiKey = '13246489';


        $omdbShowFinder = new OMDBShowFinder($client, $tokenStorage, $apiKey);
        $results = $omdbShowFinder->findByName('test');

        $category = new Category();
        $category->setName('Animation, Action, Adventure');
        $show = new Show();
        $show
            ->setName('Johnny Test')
            ->setDataSource(Show::DATA_SOURCE_OMDB)
            ->setAbstract('Not provided.')
            ->setCountry('USA, Canada')
            ->setAuthor($user)
            ->setReleaseDate(new \DateTime('01 Jan 2005'))
            ->setMainPicture('https://ia.media-imdb.com/images/M/MV5BYzc3OGZjYWQtZGFkMy00YTNlLWE5NDYtMTRkNTNjODc2MjllXkEyXkFqcGdeQXVyNjExODE1MDc@._V1_SX300.jpg')
            ->setCategory($category);
        $this->assertSame([$show], $results);
    }
}