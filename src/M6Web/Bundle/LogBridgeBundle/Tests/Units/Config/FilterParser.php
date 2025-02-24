<?php

namespace M6Web\Bundle\LogBridgeBundle\Tests\Units\Config;

use M6Web\Bundle\LogBridgeBundle\Tests\Units\BaseTest;  
use M6Web\Bundle\LogBridgeBundle\Config;

/**
 * FilterParser
 */
class FilterParser extends BaseTest
{
    private function getParser(): Config\FilterParser
    {
        return new Config\FilterParser($this->getMockedRouter());
    }

    private function getConfig(): array
    {
        return [
            'filter_un' => [
                'route' =>  'route_name',
                'method' => null,
                'status' => null
            ],
            'filter_deux' => [
                'route' =>  'route_name',
                'method' => null,
                'status' => [404, 422, 500]
            ],
            'filter_trois' => [
                'route' =>  'route_name',
                'method' => ['PUT', 'POST'],
                'status' => null
            ],
            'filter_quatre' => [
                'route' =>  'route_name',
                'method' => ['PUT'],
                'status' => [200]
            ],
            'filter_route_invalid' => [
                'route' => 'invalid_route',
                'method' => ['PUT'],
                'status' => [200]
            ],
            'filter_method_invalid' => [
                'route' => 'route_name',
                'method' => 'PUT',
                'status' => [200]
            ],
            'filter_status_invalid' => [
                'route' => 'route_name',
                'method' => ['PUT'],
                'status' => 200
            ]
        ];
    }

    public function testValidParse(): void
    {
        $config = $this->getConfig();

        $this
            ->if($parser = $this->getParser())
            ->then
                ->object($filter = $parser->parse('filter_un', $config['filter_un']))
                    ->isInstanceOf('M6Web\Bundle\LogBridgeBundle\Config\Filter')
                ->string($filter->getName())
                    ->isIdenticalTo('filter_un')
                ->string($filter->getRoute())
                    ->isIdenticalTo($config['filter_un']['route'])
                ->variable($filter->getMethod())
                    ->isIdenticalTo($config['filter_un']['method'])
                ->variable($filter->getStatus())
                    ->isIdenticalTo($config['filter_un']['status'])

                ->object($filter = $parser->parse('filter_deux', $config['filter_deux']))
                    ->isInstanceOf('M6Web\Bundle\LogBridgeBundle\Config\Filter')
                ->string($filter->getName())
                    ->isIdenticalTo('filter_deux')
                ->string($filter->getRoute())
                    ->isIdenticalTo($config['filter_deux']['route'])
                ->variable($filter->getMethod())
                    ->isIdenticalTo($config['filter_deux']['method'])
                ->variable($filter->getStatus())
                    ->isIdenticalTo($config['filter_deux']['status'])

                ->object($filter = $parser->parse('filter_trois', $config['filter_trois']))
                    ->isInstanceOf('M6Web\Bundle\LogBridgeBundle\Config\Filter')
                ->string($filter->getName())
                    ->isIdenticalTo('filter_trois')
                ->string($filter->getRoute())
                    ->isIdenticalTo($config['filter_trois']['route'])
                ->variable($filter->getMethod())
                    ->isIdenticalTo($config['filter_trois']['method'])
                ->variable($filter->getStatus())
                    ->isIdenticalTo($config['filter_trois']['status'])

                ->object($filter = $parser->parse('filter_quatre', $config['filter_quatre']))
                    ->isInstanceOf('M6Web\Bundle\LogBridgeBundle\Config\Filter')
                ->string($filter->getName())
                    ->isIdenticalTo('filter_quatre')
                ->string($filter->getRoute())
                    ->isIdenticalTo($config['filter_quatre']['route'])
                ->variable($filter->getMethod())
                    ->isIdenticalTo($config['filter_quatre']['method'])
                ->variable($filter->getStatus())
                    ->isIdenticalTo($config['filter_quatre']['status'])
        ;

    }

    public function testInvalidRoute(): void
    {
        $config = $this->getConfig()['filter_route_invalid'];

        $this
            ->if($parser = $this->getParser())
            ->then
                ->exception(function() use ($parser, $config) {
                    $parser->parse('filter_route_invalid', $config);
                })
                ->isInstanceOf(\M6Web\Bundle\LogBridgeBundle\Config\ParseException::class)
                ->message
                    ->contains('Undefined route')
        ;
    }

    public function testInvalidMethod(): void
    {
        $config = $this->getConfig()['filter_method_invalid'];

        $this
            ->if($parser = $this->getParser())
            ->then
                ->exception(function() use ($parser, $config) {
                    $parser->parse('filter_method_invalid', $config);
                })
                ->isInstanceOf(\TypeError::class)
                ->message
                    ->contains('must be of type ?array, string given')
        ;
    }

    public function testInvalidStatus(): void
    {
        $config = $this->getConfig()['filter_status_invalid'];

        $this
            ->if($parser = $this->getParser())
            ->then
                ->exception(function() use ($parser, $config) {
                    $parser->parse('filter_status_invalid', $config);
                })
                ->isInstanceOf(\TypeError::class)
                ->message
                    ->contains('must be of type ?array, int given')
        ;
    }
}
