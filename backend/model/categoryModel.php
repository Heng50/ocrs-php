<?php

    class Category{
        private $pdo;

        public function __construct($pdo){
            $this->pdo = $pdo;
        }

        public function getAllCategory(){
            $sql = "SELECT * FROM categories";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();

            if(!$result){
                return 1;
            }
            return $result;
        }

        public function selectOption(){
            $sql = "SELECT category_id, category_name FROM categories";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();
            if(!$result){
                return 1;
            }
            return $result;
        }

        public function addCategory(){
            $category_name = $_POST['category_name'];

            $sql = "SELECT COUNT(*) as count FROM categories WHERE category_name = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$category_name]);
            $result = $stmt->fetch();
            if($result['count'] > 0){
                return 1;
            }

            $sql = "INSERT INTO categories (category_name, created_at, updated_at) VALUES (?, NOW(), NOW())";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$category_name]);
            if(!$result){
                return 2;
            }

            return 0;
        }

        public function editCategory(){
            $id = $_POST['category_id'];
            $category_name = $_POST['category_name'];

            $sql = "SELECT COUNT(*) as count FROM categories WHERE category_name = ? AND category_id != ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$category_name, $id]);
            $result = $stmt->fetch();
            if($result['count'] > 0){
                return 1;
            }

            $sql = "UPDATE categories SET category_name = ?, updated_at = NOW() WHERE category_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$category_name, $id]);
            if(!$result){
                return 2;
            }

            return 0;
        }

        public function deleteCategory(){
            $id = $_POST['category_id'];
            
            $sql = "DELETE FROM categories WHERE category_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$id]);
            if(!$result){
                return 1;
            }
            return 0;
        }
    }
?>