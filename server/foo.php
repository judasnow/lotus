<?php
abstract class Pizza {
    abstract function remA();
}

// 底盘
class Crust extends Pizza {
    function remA() {
        return new Crust();
    }
}

class Cheese extends Pizza {
    private $p;

    function __construct(Pizza $_p) {
        $this->p = $_p;
    }

    function remA() {
        return new Cheese($this->p->remA());
    }
}

// 橄榄油
class Olive extends Pizza {
    private $p;

    function __construct(Pizza $_p) {
        $this->p = $_p;
    }

    function remA() {
        return new Olive($this->p->remA());
    }
}

// 鳀
class Anchovy extends Pizza {
    private $p;

    function __construct(Pizza $_p) {
        $this->p = $_p;
    }

    function remA() {
        return new Crust();
    }
}

// 香肠
class Sausage extends Pizza {
    private $p;

    function __construct(Pizza $_p) {
        $this->p = $_p;
    }

    function remA() {
        return new Sausage($this->p->remA());
    }
}

//var_dump(
//( new Anchovy(
//    new Olive(
//        new Anchovy(
//            new Anchovy(
//                new Cheese(
//                    new Crust()))))) )->remA());

(new Anchovy(
    new Crust()
))->remA();
