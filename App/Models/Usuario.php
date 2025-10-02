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
            $query = "INSERT INTO usuarios(nome, email, senha) VALUES (:nome, :email, :senha)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':nome', $this->__get('nome'));
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->bindValue(':senha', $this->__get('senha'));
            $stmt->execute();

            return $this;
        }

        public function validaCadastro() {
            $valido = true;

            if(strlen($this->__get('nome') < 3) || strlen($this->__get('email') < 3) || strlen($this->__get('senha') < 3)) {
                $valido = false;
            }

            return $valido;
        }

        public function autenticar() {
            $query = "SELECT id, nome, email FROM usuarios WHERE email = :email AND senha = :senha";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->bindValue(':senha', $this->__get('senha'));
            $stmt->execute();

            $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

            if(!empty($usuario['id']) && !empty($usuario['nome']) ) {
                $this->__set('id', $usuario['id']);
                $this->__set('nome', $usuario['nome']);
            }

            return $this;
        }

        public function getSpecificUser() {
            $query = "SELECT u.nome, u.id, u.email, (
                                SELECT COUNT(*) 
                                FROM usuarios_seguidores as us 
                                WHERE us.id_usuario = :id AND us.id_follower = u.id
                            ) as seguindo 
                      FROM usuarios u WHERE nome LIKE :nome and id NOT IN (:id)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
            $stmt->bindValue(':id', $this->__get('id'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function getAllUsers() {
            $query = "SELECT 
                            u.nome,
                            u.id,
                            u.email,
                            (
                                SELECT COUNT(*) 
                                FROM usuarios_seguidores as us 
                                WHERE us.id_usuario = :id AND us.id_follower = u.id
                            ) as seguindo 
                      FROM 
                            usuarios as u
                      WHERE id NOT IN(:id)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $this->__get('id'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function getUserPerEmail() {
            $query = "SELECT nome, email FROM usuarios WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':email', $this->__get('email'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function followUser($id_user) {
            $query = "INSERT INTO usuarios_seguidores(id_usuario, id_follower) VALUES (:id_usuario, :id_follower)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->bindValue(':id_follower', $id_user);
            $stmt->execute();

            return true;
        }

        public function unfollowUser($id_user) {
            $query = "DELETE FROM usuarios_seguidores WHERE id_usuario = :id_usuario AND id_follower = :id_follower";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id'));
            $stmt->bindValue(':id_follower', $id_user);
            $stmt->execute();

            return true;
        }

        public function getInfoUser(){
            $query = "SELECT 
                            u.nome,
                            (SELECT COUNT(*) FROM tweets as t WHERE t.id_usuario = u.id) as tweets,
                            (SELECT COUNT(*) FROM usuarios_seguidores as us WHERE us.id_usuario = u.id)  as seguindo, (SELECT COUNT(*) FROM usuarios_seguidores as us WHERE us.id_follower = u.id) as seguidores FROM usuarios as u WHERE u.id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $this->__get('id'));
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
    }