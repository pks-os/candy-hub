<?php

namespace GetCandy\Api\Discounts;

class CriteriaSet
{
    protected $sets = [];

    public $scope;

    public $outcome;

    public function add($set)
    {
        $classname = config('getcandy.discounters.' . $set->type);
        $criteria = new $classname;
        $criteria->setCriteria($set->criteria);
        $this->sets[] = $criteria;
    }

    public function count()
    {
        return count($this->sets);
    }

    public function getSets()
    {
        return $this->sets;
    }

    public function process($user, $product = null, $basket = null)
    {
        $apply = false;
        foreach ($this->sets as $set) {
            $apply = $set->check($user, $product, $basket);
        }
        return $apply;
    }
}