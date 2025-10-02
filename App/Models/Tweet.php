<?php

    namespace App\Models;

    use MF\Model\Model;

    class Tweet extends Model{
        private $id;
        private $id_usuario;
        private $tweet;
        private $data;

        public function __get($attr) {
            return $this->$attr;
        }

        public function __set($attr, $v) {
            $this->$attr = $v;
        }

        public function salvar() {
            $query = "INSERT INTO tweets(id_usuario, tweet) VALUES (:id_usuario, :tweet)";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->bindValue(':tweet', $this->__get('tweet'));
            $stmt->execute();

            return $this;
        }

        public function remover() {
            $query = "DELETE FROM tweets WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $this->__get('id'));
            $stmt->execute();

            return $this;
        }

        public function getAll() {
            $query = "SELECT 
                t.id, 
                t.tweet, 
                DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') as data,
                u.nome,
                t.id_usuario
                FROM tweets t
                INNER JOIN usuarios u
                ON t.id_usuario=u.id
                WHERE id_usuario = :id_usuario OR
                t.id_usuario IN (
                    SELECT 
                        us.id_follower
                    FROM usuarios_seguidores us
                    WHERE us.id_usuario = :id_usuario
                )
                ORDER BY t.data DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

            public function getPerPage($limit, $offset) {
            $query = "SELECT 
                t.id, 
                t.tweet, 
                DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') as data,
                u.nome,
                t.id_usuario
                FROM tweets t
                INNER JOIN usuarios u
                ON t.id_usuario=u.id
                WHERE id_usuario = :id_usuario OR
                t.id_usuario IN (
                    SELECT 
                        us.id_follower
                    FROM usuarios_seguidores us
                    WHERE us.id_usuario = :id_usuario
                )
                ORDER BY 
                    t.data DESC
                LIMIT 
                    $limit 
                OFFSET 
                    $offset";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function getTotalTweets() {
            $query = "SELECT 
                        COUNT(*) as total 
                      FROM 
                        tweets t
                      LEFT JOIN
                        usuarios u ON (t.id_usuario=u.id)
                      WHERE 
                        id_usuario = :id_usuario";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
            $stmt->execute();

            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }
    }