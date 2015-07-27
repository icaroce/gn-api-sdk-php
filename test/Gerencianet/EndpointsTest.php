<?php

namespace Gerencianet;

class EndpointsTest extends \PHPUnit_Framework_TestCase
{
    private $options;
    private $requester;

    protected function setUp()
    {
        $this->options = ['sandbox' => true];
        $this->requester = $this->getMockBuilder('ApiRequest')
                                ->setMethods(array('send'))
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->requester->method('send')
                        ->willReturn(true);
    }

    /**
     * @test
     */
    public function shouldForwarWithQueryString()
    {
        $method = 'post';
        $route = '/charge?id=90&token=2039480293840923&name=francisco&lastname=carvalho';
        $body = ['content' => 'data'];

        $this->requester->expects($this->once())
                        ->method('send')
                        ->with($this->equalTo($method),
                               $this->equalTo($route),
                               $this->equalTo($body));

        $endpoints = new Endpoints($this->options, $this->requester);
        $params = ['id' => '90',
                   'token' => '2039480293840923',
                   'name' => 'francisco',
                   'lastname' => 'carvalho', ];

        $endpoints->createCharge($params, $body);
    }

    /**
     * @test
     * @expectedException Exception
     */
    public function shouldThrowExceptionForWrongMethod()
    {
        $method = 'post';
        $route = '/charge?id=90&token=2039480293840923&name=francisco&lastname=carvalho';
        $body = ['content' => 'data'];

        $this->requester->expects($this->never())
                        ->method('send');

        $endpoints = new Endpoints($this->options, null);
        $params = ['id' => '90',
                   'token' => '2039480293840923',
                   'name' => 'francisco',
                   'lastname' => 'carvalho', ];

        $endpoints->notAMethod($params, $body);
    }

    /**
     * @test
     */
    public function shouldForwarWithParams()
    {
        $method = 'get';
        $route = '/charge/90';

        $this->requester->expects($this->once())
                        ->method('send')
                        ->with($this->equalTo($method),
                               $this->equalTo($route),
                               $this->equalTo([]));

        $endpoints = new Endpoints($this->options, $this->requester);
        $params = ['id' => '90'];

        $endpoints->detailCharge($params, []);
    }

    /**
     * @test
     */
    public function shouldForwarWithParamsAndQueryString()
    {
        $method = 'get';
        $route = '/charge/90?token=2039480293840923';

        $this->requester->expects($this->once())
                        ->method('send')
                        ->with($this->equalTo($method),
                               $this->equalTo($route),
                               $this->equalTo([]));

        $endpoints = new Endpoints($this->options, $this->requester);
        $params = ['id' => '90',
                   'token' => '2039480293840923', ];

        $endpoints->detailCharge($params, []);
    }
}