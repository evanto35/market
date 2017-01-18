<?php

/**
 * Description of User
 *
 * @author rodnoy
 */
class User {

    public static function register($name, $password, $email) {
        
        $db = Db::getConnection();
        
        $sql = 'INSERT INTO user (name, password, email) '
                . 'VALUES (:name, :password, :email)';
        
        $result = $db->prepare($sql);
        
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        
        return $result->execute();
    }

    /**
     * Проверяет имя: не меньше , чем 2 символа
     * @param string $name
     * @return boolean
     */
    public static function checkName($name) {
        if (strlen($name) >= 2) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет пароль: не меньше, чем 6 символов
     * @param string $password
     * @return boolean
     */
    public static function checkPassword($password) {
        if (strlen($password) >= 6) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет email
     * @param string $email
     * @return boolean
     */
    public static function checkEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    /**
     * Проверят, существует ли такой же(введеный при регистрации) $email в базе данных
     * @param string $email
     * @return boolean
     */
    public static function checkEmailExists($email) {

        $db = Db::getConnection();

        $sql = "SELECT count(*) FROM user WHERE email = :email";

        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->execute();

        if ($result->fetchColumn())
            return true;
        return false;
    }
    
    /**
     * Проверяем существует ли пользователь с заданными $email и $password
     * @param string $email
     * @param string $password
     * @return mised: integer user id or false
     */
    public static function checkUserData($email, $password){
        
        $db = Db::getConnection();
        
        $sql = "SELECT * FROM user WHERE email = :email AND password = :password";
        
        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        $result->execute();
        
        $user = $result->fetch();
        if($user){
            return $user['id'];
        }
        
        return false;        
    }
    
    /**
     * Запоминаем пользователя
     * @param integer $userId
     */
    public static function auth($userId){
        session_start();
        $_SESSION['user'] = $userId;
    }
    
    public static function checkLogged(){
        
        session_start();
        //Если сессия есть - вернем идентификатор поользователя
        if(isset($_SESSION['user'])){
            return $_SESSION['user'];
        }else{
            header('Location: /user/login');       
        }
    }

}
