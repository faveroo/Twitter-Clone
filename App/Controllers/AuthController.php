<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AuthController extends Action {
    public function autenticar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = Container::getModel('Usuario');
            $usuario->__set('email', $_POST['email']);
            $usuario->__set('senha', $_POST['senha']);

            $return = $usuario->autenticar();
            echo "Olá!, {$return['nome']}!";
        }
    } 
}