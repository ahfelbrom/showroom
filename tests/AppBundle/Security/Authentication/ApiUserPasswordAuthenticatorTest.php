<?php

namespace Tests\AppBundle\Security\Authentication;

use AppBundle\Entity\User;
use AppBundle\Security\Authentication\ApiUserPasswordAuthenticator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\Argon2iPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class ApiUserPasswordAuthenticatorTest extends TestCase
{
	public function testGetCredentialsFromHeaders()
	{
		$encoderFactory = new EncoderFactory([]);

		$request = new Request();
		$request->headers->add(['X-USERNAME' => 'ME']);
		$request->headers->add(['X-PASSWORD' => 'mypwd']);

		$authenticator = new ApiUserPasswordAuthenticator($encoderFactory);
		$result = $authenticator->getCredentials($request);

		$expected = ['username' => 'ME', 'password' => 'mypwd'];

		$this->assertSame($expected, $result);
		
	}

	/**
     * @dataProvider getHeaders
     */
	public function testGetCredentialsWithoutAnyHeaders($headers)
	{
		$encoderFactory = new EncoderFactory([]);

		$request = new Request();
		$request->headers->add($headers);

		$authenticator = new ApiUserPasswordAuthenticator($encoderFactory);
		$result = $authenticator->getCredentials($request);

		$this->assertNull($result);

	}

	public function getHeaders()
	{
		return [
			[['X-USERNAME' => 'ME']],
			[['X-PASSWORD' => 'mypwd']],
		];
	}

	public function testCheckCredentialsAreCorrect()
	{
		$encoder = new Argon2iPasswordEncoder();

		$encoderFactory = $this
		    ->getMockBuilder(EncoderFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $encoderFactory->method('getEncoder')->willReturn($encoder);

		$authenticator = new ApiUserPasswordAuthenticator($encoderFactory);

		$user = new User();
		$user->setPassword('$argon2id$v=19$m=65536,t=2,p=1$qhXlt5Ez1ago3+MLn5WyxQ$NzRrfyjkOKR6XctaEBaS7qbkx71/5+O4g91MNb4EpuU');
		$credentials = ['username' => 'ME', 'password' => 'mypwd'];

		$result = $authenticator->checkCredentials($credentials, $user);

		$this->assertSame(true, $result);
	}

    /**
     * @expectedException Symfony\Component\Security\Core\Exception\AuthenticationException
     */
	public function testCheckCredentialsAreWrong()
	{
		$encoder = new Argon2iPasswordEncoder();

	    $encoderFactory = $this
		    ->getMockBuilder(EncoderFactoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $encoderFactory->method('getEncoder')->willReturn($encoder);

		$authenticator = new ApiUserPasswordAuthenticator($encoderFactory);

		$credentials = ['username' => 'ME', 'password' => 'wrongPassword'];
		$user = new User();
		$user->setPassword('$argon2id$v=19$m=65536,t=2,p=1$qhXlt5Ez1ago3+MLn5WyxQ$NzRrfyjkOKR6XctaEBaS7qbkx71/5+O4g91MNb4EpuU');

		$result = $authenticator->checkCredentials($credentials, $user);
	}
}