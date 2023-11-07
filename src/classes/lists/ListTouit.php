<?php

namespace iutnc\touiteur\lists;

class ListTouit {
    private int $nbTouits;
    private array $touits = [];

    /**
     * @param int $nbTouits
     * @param array $touits
     */
    public function __construct(int $nbTouits, array $touits =[]) {
        $this->nbTouits = $nbTouits;
        $this->touits = $touits;
    }


}