<?php
class cart {
    private $cart = [];

    function __construct() {
        session_start();
        $this->cart = $_SESSION['cart'];
    }

    public function getCart() {
       return $this->cart;
    }

    public function addItem($id) {
        //array_push($this->cart, $id);
        if (isset($this->cart[$id]) ) {
            $this->cart[$id]++;
        } else {
            $this->cart[$id] = 1;
        }
        $_SESSION['cart'] = $this->cart;
    }

    public function deleteItem($id) {
        if (isset($this->cart[$id]) ) {
            if($this->cart[$id] >= 1) {
                $this->cart[$id]--;
            } 
            if ($this->cart[$id] == 0) {
                unset($this->cart[$id]);
            }
        }
        $_SESSION['cart'] = $this->cart;
    }
    
    public function getItem($key) {
        return $this->cart[$key];
    }
    
    public function flush() {
        unset($this->cart);
        $this->cart = []; // re init
        $_SESSION['cart'] = $this->cart;
    }
}
