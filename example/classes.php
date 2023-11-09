<?php

class Customer
{
  public $number;
  public $name;
  public $addresses = [];
  public $phone = [];
  public $orders = [];

  public function __construct($name)
  {
    $this->name = $name;
  }

  public function add_address($address)
  {
    $this->addresses[] = $address;
  }

  public function add_phone($phone)
  {
    $this->phone[] = $phone;
  }

  public function add_order($order)
  {
    $this->orders[] = $order;
  }
}

class Address
{
  public $street;
  public $housenumber;
  public $zip;
  public $city;
  public $country;
}

class Phone
{
  public $number;
}

class Order
{
  public $date;
  public $products = [];
}

class Product
{
  public $name;
  public $price;
  public $tax;
  public $description;
}
