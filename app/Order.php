<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;

class Order extends Model
{
    public function __construct(){
      $order = new \stdClass;
      $order->pizzas = [];
      $order->total = 0;
      $this->saveToSession($order);
    }

    public static function addPizza($pizza, $size){
      $order = Session::get('order');
      $tempPizza = new \stdClass;
      $tempPizza->name = $pizza->name;
      $tempPizza->size = $size;

      foreach($pizza->types as $type){
        if($type->size->name == $size){
          $tempPizza->price = $type->price;
        }
      }

      $order->total += $tempPizza->price;
      array_push($order->pizzas, $tempPizza);
    }

    public static function getPizzas(){
      $order = Session::get('order');
      return $order->pizzas;
    }

    public function getTotal(){
      $order = Session::get('order');
      return $this->getPriceInPounds($order->total);
    }

    public function saveToSession($order){
      if(!Session::has('order')){
        Session::set('order', $order);
      }
      Session::save();
    }

    public function getPriceInPounds($price){
      return number_format($price / 100, 2);
    }
}