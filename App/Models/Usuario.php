<?php

    namespace App\Models;

    use MF\Model\Model;

    class Usuario extends Model{
        private $id; 
        private $nome;
        private $email;
        private $senha;

        public function __get($attr) {
            return $this->$attr;
        }

        public function __set($attr, $v) {
            $this->$attr = $v;
        }

        public function salvar() {
            $query = "INSERT INTO (nome, email, senha) VALUES (:nome, :email :senha)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':nome', $this->__get('nome'));
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->bindValue(':senha', $this->__get('senha'));
            $stmt->execute();

            return $this;
        }
    }