<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {
    public function timeline() {
        $this->view->login = isset($_GET['tweet']) ? $_GET['tweet'] : '';

        $this->validateAuth();

        $tweet = Container::getModel('Tweet');
        $tweet->__set('id_usuario', $_SESSION['id']);

        $tweets = $tweet->getAll();
        $this->view->tweets = $tweets;
        $this->render('timeline');
    }

    public function tweet() {
        $this->validateAuth();

        $tweet = Container::getModel('Tweet');
        $tweet->__set('tweet', $_POST['tweet']);
        $tweet->__set('id_usuario', $_SESSION['id']);
        
        if(!empty($_POST['tweet'])) {
            $tweet->salvar();
            $this->redirect('/timeline');
        } elseif($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->redirect('/timeline?tweet=erro');
        } else {
            $this->redirect('/timeline');
        }

    }

    public function remover() {

        $this->validateAuth();

        if(!isset($_POST['id_tweet'])) {
            $this->redirect('/timeline');
        }

        $tweet = Container::getModel('Tweet');
        $tweet->__set('id', $_POST['id_tweet']);
        $tweet->remover();
        $this->redirect('/timeline');
    }

    public function validateAuth() {
        
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if(!isset($_SESSION['id']) || !isset($_SESSION['nome'])) {
            $this->redirect('/?login=erro');
        }
    }
}