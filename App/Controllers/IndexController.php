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

		$this->view->usuario = array(
					'nome' => '',
					'email' => '',
					'senha' => ''
				);
		
		$this->render('subscribe');
	}

	public function registrar() {
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$usuario = Container::getModel('Usuario');
		$usuario->__set('nome', $_POST['nome']);
		$usuario->__set('email', $_POST['email']);
		$usuario->__set('senha', $_POST['senha']);

		if($usuario->validaCadastro() && count($usuario->getUserPerEmail()) == 0) {
				$usuario->salvar();
				$this->render('cadastro');
			} else {

				$this->view->usuario = array(
					'nome' => $_POST['nome'],
					'email' => $_POST['email'],
					'senha' => $_POST['senha']
				);

				$this->view->erroCadastro = true;
				$this->render('subscribe');
			}
		} else {
			$this->redirect('/');
		}
	}
}

?>