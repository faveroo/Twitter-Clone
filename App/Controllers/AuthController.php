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

            $usuario->autenticar();

            if(!empty($usuario->__get('id')) && !empty($usuario->__get('nome'))) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['id'] = $usuario->__get('id');
                $_SESSION['nome'] = $usuario->__get('nome');

                $this->redirect('/timeline');

            } else {
                $this->redirect("/?login=erro");
            }
        } else {
            $this->redirect('/');
        }
    }
    
    public function sair() {
        session_start();
        session_destroy();
        $this->redirect('/');
    }
}