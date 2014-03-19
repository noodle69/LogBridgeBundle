<?php

namespace M6Web\Bundle\LogBridgeBundle\Matcher;

/**
 * MatcherProxy
 */
class MatcherProxy implements MatcherInterface
{
    /**
     * @var BuilderInterface
     */
    private $builder;

    /**
     * @var MatcherInterface
     */
    private $matcher;

    /**
     * __construct
     *
     * @param BuilderInterface $builder Matcher builder
     */
    public function __construct(BuilderInterface $builder)
    {
        $this->builder = $builder;
        $this->matcher = $builder->getMatcher();
    }

    /**
     * match
     *
     * @param string  $route  Route name
     * @param string  $method Method name
     * @param integer $status Http code status
     *
     * @return boolean
     */   
    public function match($route, $method, $status)
    {
        return $this->matcher->match($route, $method, $status);
    }

    /**
     * addFilter
     *
     * @param string $filter Filter
     *
     * @return MatcherProxy
     */
    public function addFilter($filter)
    {
        $this->matcher->addFilter($filter);

        return $this;
    }

    /**
     * setFilters
     *
     * @param array $filters Filter list
     *
     * @return MatcherProxy
     */
    public function setFilters(array $filters, $overwrite = false)
    {
        $this->matcher->setFilters($filters, $overwrite);

        return $this;
    }

    /**
     * getFilters
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->matcher->getFilters();
    }

    /**
     * hasFilter
     *
     * @param string $filter Filter
     *
     * @return boolean
     */
    public function hasFilter($filter)
    {
        return $this->matcher->hasFilter($filter);
    }

    /**
     * getMatcher
     *
     * @return MatcherInterface
     */
    public function getMatcher()
    {
        return $this->matcher;
    }

    /**
     * getBuilder
     *
     * @return BuilderInterface
     */
    public function getBuilder()
    {
        return $this->builder;
    }

}
