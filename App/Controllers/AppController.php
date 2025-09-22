<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {
    public function timeline() {
        $this->view->login = isset($_GET['tweet']) ? $_GET['tweet'] : '';
        session_start();

        if(!isset($_SESSION['id']) && !isset($_SESSION['nome'])) {
            $this->redirect('/?login=erro');
        }

        $this->render('timeline');
    }

    public function tweet() {
        session_start();

        if(!isset($_SESSION['id']) && !isset($_SESSION['nome'])) {
            $this->redirect('/?login=erro');
        }

        $tweet = Container::getModel('Tweet');
        $tweet->__set('tweet', $_POST['tweet']);
        $tweet->__set('id_usuario', $_SESSION['id']);
        
        if(!empty($_POST['tweet'])) {
            $tweet->salvar();
            $this->redirect('/timeline');
        } else {
            $this->redirect('/timeline?tweet=erro');
        }

    }
}