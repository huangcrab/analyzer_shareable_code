<?php 
    class User {
        private $db;

        public function __construct(){
            $this->db = new Database;
        }
        
        public function getUsers(){
            $this->db->query('SELECT * FROM ana_users');

            $results = $this->db->resultSet();
            
            return $results;
        }   
        public function getPendingUsers(){
            $this->db->query('SELECT * FROM ana_pending_users');

            $results = $this->db->resultSet();
            
            return $results;
        }
        //Register user
        public function register($data){
            $this->db->query('INSERT INTO ana_users (name, email, password) VALUES(:name, :email, :password)');
            //bind values
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':password', $data['password']);

            //excute
            if($this->db->execute()){
                return true;
            } else {
                return false;
            }
        }

        public function login($email, $password){
            $this->db->query('SELECT * FROM ana_users WHERE email = :email');
            $this->db->bind(':email', $email);

            $row = $this->db->single();
            
            $hashed_password = $row->password;
            if(password_verify($password, $hashed_password)){
                return $row;
            } else {
                return false;
            }
        }
        public function updatePassword($email, $oldPassword, $newPassword){
            $this->db->query('SELECT * FROM ana_users WHERE email = :email');
            $this->db->bind(':email', $email);

            $row = $this->db->single();
            $hashed_password = $row->password;
            if(password_verify($oldPassword, $hashed_password)){
                $this->db->query('UPDATE ana_users SET password = :password WHERE email = :email');
            //bind values
                $this->db->bind(':password', $newPassword);
                $this->db->bind(':email', $email);
            //excute
                if($this->db->execute()){
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
        public function findUserByEmail($email){
            $this->db->query('SELECT * FROM ana_users WHERE email = :email');
            $this->db->bind(':email', $email);

            $row = $this->db->single();

            if($this->db->rowCount() > 0){
                return true;
            } else {
                return false;
            }
        }
        public function findPendingUserByEmail($email){
            $this->db->query('SELECT * FROM ana_pending_users WHERE email = :email');
            $this->db->bind(':email', $email);

            $row = $this->db->single();

            if($this->db->rowCount() > 0){
                return true;
            } else {
                return false;
            }
        }

        public function addPendingUser($email){
            $this->db->query('INSERT INTO ana_pending_users (email) VALUES (:email)');
            //bind values
            $this->db->bind(':email', $email);
            //excute
            if($this->db->execute()){
                return true;
            } else {
                return false;
            }
        }
        public function getUserById($id){
            $this->db->query('SELECT * FROM ana_users WHERE id = :id');
            $this->db->bind(':id', $id);

            $row = $this->db->single();

            return $row;
        }
        public function deleteUser($id){
            $this->db->query('DELETE FROM ana_users WHERE id = :id');
            //bind values
            $this->db->bind(':id', $id);
            
            //excute
            if($this->db->execute()){
                return true;
            } else {
                return false;
            }
        }
        public function deletePendingUserByEmail($email){
            $this->db->query('DELETE FROM ana_pending_users WHERE email = :email');
            //bind values
            $this->db->bind(':email', $email);
            
            //excute
            if($this->db->execute()){
                return true;
            } else {
                return false;
            }
        }
    }