<?php
    class QueryClass { 
        private $host = 'localhost';
        private $dbname = 'minorproject';
        private $user = 'root';
        private $pwd = 'user123';
    
        protected function connection() {
            $pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->user, $this->pwd);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        }
        
        public function insert_user($username){
            $sql = "insert into users (user_name) value (:value)";
            $stmt = $this->connection()->prepare($sql);
            $stmt->execute(array(':value' => $username));
         }  
         public function insert_message($message){
            $sql = "insert into messages (information) value (:value)";
            $stmt = $this->connection()->prepare($sql);
            $stmt->execute(array(':value' => $message));
         }      

    
    public function get_last_message() {
        $query = "SELECT information FROM messages  
        ORDER BY message_id DESC  
        LIMIT 1";
        $stmt = $this->connection()->prepare($query);
        $result = $stmt->execute();
        if($result) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
        return false;
    }
    
    public function count_messages() {   
        $query = "select count(message_id) as count from messages";
        $stmt = $this->connection()->prepare($query);
        $result = $stmt->execute();
        if($result) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
            }
        return false;
    }

    public function get_unlock_data() {
        $query = "select u.user_name, v.unlock_time  
        from users  u 
        join unlocktable v on v.user_id = u.user_id 
        order by v.unlock_time desc";
        $stmt = $this->connection()->prepare($query);
        $result = $stmt->execute();
        if($result) {
            $result = $stmt->fetchALL(PDO::FETCH_ASSOC);
            return $result;
        }
        return false;
    }
    public function get_users() {   
        $query = "select user_id from users ";
        $stmt = $this->connection()->prepare($query);
        $result = $stmt->execute();
        if($result) {
            $result = $stmt->fetchALL(PDO::FETCH_ASSOC);
            return $result;
            }
        return false;
    }

    public function get_lastuser_id() {   
        $query = "SELECT user_id FROM users  
            ORDER BY user_id DESC  
            LIMIT 1";
        $stmt = $this->connection()->prepare($query);
        $result = $stmt->execute();
        if($result) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['user_id'];
        }
        return false;
    }
    public function get_all_users() {   
        $query = "select user_name,password from users ";
        $stmt = $this->connection()->prepare($query);
        $result = $stmt->execute();
        if($result) {
            $result = $stmt->fetchALL(PDO::FETCH_ASSOC);
            return $result;
            }
        return false;
    }
    
  
       

    
    
} 
        //$row = $stmt->fetch(PDO::FETCH_ASSOC);
    
