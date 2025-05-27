<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Sylius\Bundle\PayumBundle\HttpClient;

use Payum\Core\HttpClientInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Sylius\Bundle\PayumBundle\HttpClient\HttpClient;

final class HttpClientTest extends TestCase
{
    /** @var ClientInterface|MockObject */
    private MockObject $clientMock;

    private HttpClient $httpClient;

    protected function setUp(): void
    {
        $this->clientMock = $this->createMock(ClientInterface::class);
        $this->httpClient = new HttpClient($this->clientMock);
    }

    public function testImplementsHttpClientInterface(): void
    {
        $this->assertInstanceOf(HttpClientInterface::class, $this->httpClient);
    }

    public function testSendsARequest(): void
    {
        /** @var RequestInterface|MockObject $requestMock */
        $requestMock = $this->createMock(RequestInterface::class);
        /** @var ResponseInterface|MockObject $responseMock */
        $responseMock = $this->createMock(ResponseInterface::class);
        $this->clientMock->expects($this->once())->method('sendRequest')->with($requestMock)->willReturn($responseMock);
        $this->assertSame($responseMock, $this->httpClient->send($requestMock));
    }
}
