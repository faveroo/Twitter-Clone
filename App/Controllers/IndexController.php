<?php

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

	public function index() {

		$this->render('index');
	}

	public function subscribe() {
		$this->view->erroCadastro = false;
		$this->render('subscribe');
	}

	public function registrar() {
		$usuario = Container::getModel('Usuario');
		$usuario->__set('nome', $_POST['nome']);
		$usuario->__set('email', $_POST['email']);
		$usuario->__set('senha', $_POST['senha']);

		if($usuario->validaCadastro() && count($usuario->getUserPerEmail()) == 0) {
				$usuario->salvar();
				$this->render('cadastro');
			} else {
				$this->view->erroCadastro = true;
				$this->render('subscribe');
		}
	}
}


?>