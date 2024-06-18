<?php

$router->get('/listings','ListingControllers@index');
$router->get('/','HomeControllers@home');
$router->get('/listings/create','ListingControllers@create',['auth']);
$router->get('/listings/search','ListingControllers@search');
$router->get('/listings/{id}','ListingControllers@details');
$router->post('/listings','ListingControllers@storeData');
$router->delete('/listings/{id}','ListingControllers@delete', ['auth']);
$router->get('/listings/edit/{id}','ListingControllers@edit', ['auth']);
$router->put('/listings/{id}','ListingControllers@update', ['auth']);

$router->get('/auth/register', 'UserControllers@register');
$router->get('/auth/login', 'UserControllers@loginView');
$router->post('/auth/register', 'UserControllers@regDB');
$router->post('/auth/logout', 'UserControllers@logout');
$router->post('/auth/login', 'UserControllers@login');


?>
