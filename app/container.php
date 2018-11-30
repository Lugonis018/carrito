<?php

use Cart\Basket\Basket;
use function DI\get;
use Slim\Views\Twig;
use Cart\Models\Product;
use Cart\Models\Order;
use Cart\Models\Customer;
use Cart\Models\Address;
use Slim\Views\TwigExtension;
use Interop\Container\ContainerInterface;
use Cart\Support\Storage\SessionStorage;
use Cart\Support\Storage\Contracts\StorageInterface;
use Cart\Validation\Contracts\ValidatorInterface;
use Cart\Validation\Validator;

return [
    'router' => get(Slim\Router::class),
    ValidatorInterface::class => function(ContainerInterface $c) {
        return new Validator;
    },
    StorageInterface::class => function(ContainerInterface $c){
        return new SessionStorage('cart');
    },
    Twig::class => function(ContainerInterface $c){
        $twig = new Twig(__DIR__ . '/../resources/views',[
            'cache' => false
        ]);

        $twig-> addExtension(new TwigExtension(
            $c->get('router'),
            $c->get('request')->getUri()
        ));

        $twig->getEnvironment()->addGlobal('basket', $c->get(Basket::class));

        return $twig;
    },
    Product::class => function(ContainerInterface $c){
        return new Product;
    },

    Order::class => function(ContainerInterface $c){
        return new Order;
    },

    Costumer::class => function(ContainerInterface $c){
        return new Costumer;
    },

    Address::class => function(ContainerInterface $c){
        return new Address;
    },

    Basket::class => function (ContainerInterface $c){
        return new Basket(
            $c->get(SessionStorage::class),
            $c->get(Product::class)
        );
    }
];