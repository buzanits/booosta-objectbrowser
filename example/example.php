<?php
require_once __DIR__ . '/vendor/autoload.php';

require_once 'classes.php';
require_once 'styles.css';

use booosta\objectbrowser\Objectbrowser;


$customer = new Customer('John Doe');
$customer->number = '001';

$address = new Address();
$address->street = 'Main Street';
$address->housenumber = '7/2';
$address->zip = '98765';
$address->city = 'Springfield';
$address->country = 'USA';

$customer->add_address($address);

$address = new Address();
$address->street = 'Harbour Street';
$address->housenumber = '33';
$address->zip = '12345';
$address->city = 'Stars Hollow';
$address->country = 'Canada';

$customer->add_address($address);

$phone = new Phone();
$phone->number = '817891768';
$customer->add_phone($phone);

$phone = new Phone();
$phone->number = '3473463737';
$customer->add_phone($phone);

$phone = new Phone();
$phone->number = '555547885';
$customer->add_phone($phone);

$whisky = new Product();
$whisky->name = 'Whisky';
$whisky->price = 154.50;
$whisky->tax = 20;
$whisky->description = 'Best Whisky in the world';

$steak = new Product();
$steak->name = 'T-Bone Steak';
$steak->price = 33;
$steak->tax = 20;
$steak->description = 'Best Steak in the world';

$ice = new Product();
$ice->name = 'Ice cream';
$ice->price = 4.50;
$ice->tax = 20;
$ice->description = 'Best ice cream in the world';

$order = new Order();
$order->date = '2023-09-09';
$order->products = [$whisky, $steak];
$customer->add_order($order);

$order = new Order();
$order->date = '2023-10-08';
$order->products = [$ice];
$customer->add_order($order);

$whisky1 = clone $whisky;   // do not use object twice, objectbrowser will detect a loop!
$steak1 = clone $steak;   // do not use object twice, objectbrowser will detect a loop!
$ice1 = clone $ice;   // do not use object twice, objectbrowser will detect a loop!

$order = new Order();
$order->date = '2023-11-09';
$order->products = [$ice1, $whisky1, $steak1];
$customer->add_order($order);


$objectbrowser = new Objectbrowser($customer);
print $objectbrowser->get_html();
