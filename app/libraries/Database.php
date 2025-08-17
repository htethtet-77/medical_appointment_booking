<?php
namespace Asus\Medical\libraries;
use PDO;             
use PDOException;   
use Exception; 
class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $pdo;
    private $stmt;
    private $error;

    public function __construct()
    {
        //to connect to the mysql database
        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->dbname;
        //mean separate different part, In this  ; means end of the host part and beginning of the database
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false // For General Error
        );

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
            // print_r($this->pdo);
            // echo "Success";
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    // public function create($table, $data)
    // {
    //     // print_r($data);  
    //     // exit;
    //     try {
    //         $column = array_keys($data);
    //         $columnSql = implode(', ', $column);
    //         $bindingSql = ':' . implode(',:', $column);
    //         //echo $bindingSql;
    //         $sql = "INSERT INTO $table ($columnSql) VALUES ($bindingSql)";
    //         // echo $sql;
    //         // exit;
    //         $stm = $this->pdo->prepare($sql);  
    //         // print_r($stm);
    //         // exit;
    //         foreach ($data as $key => $value) {
    //             // echo $key . ' => ' . $value . '<br>';
    //             // echo ':' . $key . ' => ' . $value . '<br>';

    //             $stm->bindValue(':' . $key, $value);
    //         }
    //         // print_r($stm);
    //         $status = $stm->execute();
    //         // echo $status;
    //         return ($status) ? $this->pdo->lastInsertId() : false;
    //     } catch (PDOException $e) {
    //         echo $e;
    //     }
    // }

    public function create($table, $data)
    {
        try {
            $this->pdo->beginTransaction();

            $column = array_keys($data);
            $columnSql = implode(', ', $column);
            $bindingSql = ':' . implode(',:', $column);

            $sql = "INSERT INTO $table ($columnSql) VALUES ($bindingSql)";
            $stm = $this->pdo->prepare($sql);

            foreach ($data as $key => $value) {
                $stm->bindValue(':' . $key, $value);
            }

            $status = $stm->execute();

            if (!$status) {
                $this->pdo->rollBack();
                return false;
            }

            $lastId = $this->pdo->lastInsertId();

            $this->pdo->commit();

            return $lastId;

        } catch (PDOException $e) {
            $this->pdo->rollBack();
            echo "PDOException: " . $e->getMessage();
            return false;
        }
    }



    // Update Query
    public function update($table, $id, $data)
    {
        if (isset($data['id'])) {
            unset($data['id']);
        }

        try {
            $this->pdo->beginTransaction();

            // Lock the row first
            $lockSql = "SELECT * FROM $table WHERE id = :id FOR UPDATE";
            $lockStmt = $this->pdo->prepare($lockSql);
            $lockStmt->bindValue(':id', $id);
            $lockStmt->execute();

            // Prepare update statement
            $columns = array_keys($data);
            $columns = array_map(function($item) {
                return $item . '=:' . $item;
            }, $columns);
            $bindingSql = implode(',', $columns);

            $sql = 'UPDATE ' .  $table . ' SET ' . $bindingSql . ' WHERE `id` =:id';
            $stm = $this->pdo->prepare($sql);

            $data['id'] = $id;

            foreach ($data as $key => $value) {
                $stm->bindValue(':' . $key, $value);
            }

            $status = $stm->execute();

            if (!$status) {
                $this->pdo->rollBack();
                return false;
            }

            $this->pdo->commit();

            return $status;

        } catch (PDOException $e) {
            $this->pdo->rollBack();
            echo $e->getMessage();
            return false;
        }
    }


    public function delete($table, $id)
    {
        try {
            $this->pdo->beginTransaction();

            // Lock the row first
            $lockSql = "SELECT * FROM $table WHERE id = :id FOR UPDATE";
            $lockStmt = $this->pdo->prepare($lockSql);
            $lockStmt->bindValue(':id', $id);
            $lockStmt->execute();

            $sql = 'DELETE FROM ' . $table . ' WHERE `id` = :id';
            $stm = $this->pdo->prepare($sql);
            $stm->bindValue(':id', $id);
            $success = $stm->execute();

            if (!$success) {
                $this->pdo->rollBack();
                return false;
            }

            $this->pdo->commit();

            return true;

        } catch (PDOException $e) {
            $this->pdo->rollBack();
            echo $e->getMessage();
            return false;
        }
    }


    public function columnFilter($table, $column, $value)
    {
        // $sql = 'SELECT * FROM ' . $table . ' WHERE `' . $column . '` = :value';
        $sql = 'SELECT * FROM ' . $table . ' WHERE `' . str_replace('`', '', $column) . '` = :value';
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':value', $value);
        $success = $stm->execute();
        $row = $stm->fetch(PDO::FETCH_ASSOC);
        return ($success) ? $row : [];
    }

    

    public function columnFilterAll($table, $column, $value)
    {
        $sql = 'SELECT * FROM ' . $table . ' WHERE `' . str_replace('`', '', $column) . '` = :value';
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':value', $value);
        $success = $stm->execute();

        $rows = $stm->fetchAll(PDO::FETCH_ASSOC);
        return ($success) ? $rows : [];
    }




    public function loginCheck($email, $password)
    {
        $sql = 'SELECT * FROM users WHERE `email` = :email AND `password` = :password';
        // echo $sql;
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':email', $email);
        $stm->bindValue(':password', $password);
        $success = $stm->execute();
        $row = $stm->fetch(PDO::FETCH_ASSOC);
        return ($success) ? $row : [];
    }
        public function setLogin($id)
    {
            $sql = 'UPDATE users SET is_login = :value WHERE id = :id';
            $stm = $this->pdo->prepare($sql);
            $stm->bindValue(':value', 1);
            $stm->bindValue(':id', $id);
            $success = $stm->execute();
            $stm->closeCursor();    // to solve PHP Unbuffered Queries
            $row = $stm->fetch(PDO::FETCH_ASSOC);
            return ($success) ? $row : [];
    }
 

    public function unsetLogin($id)
    {
       try{ 
           $sql = "UPDATE users SET is_login = :false WHERE id = :id"; //is_login = :false .is a placeholder to indicate user login(true) or not(false)
           $stm = $this->pdo->prepare($sql);
           $stm->bindValue(':false','0');
           $stm->bindValue(':id',$id);
           $success = $stm->execute();
           $row     = $stm->fetch(PDO::FETCH_ASSOC);
           return ($success) ? $row : [];
        }
        catch( Exception $e)
        {
            echo($e);
        }
    }

    public function readAll($table)
    {
        $sql = 'SELECT * FROM ' . $table;
        // print_r($sql);
        $stm = $this->pdo->prepare($sql);
        $success = $stm->execute();
        $row = $stm->fetchAll(PDO::FETCH_ASSOC);
        return ($success) ? $row : [];
    }

    public function getById($table, $id)
    {
        $sql = 'SELECT * FROM ' . $table . ' WHERE id =:id';
        // print_r($sql);
        $stm = $this->pdo->prepare($sql);
        $stm->bindValue(':id', $id);
        $success = $stm->execute();
        $row = $stm->fetch(PDO::FETCH_ASSOC);
        return ($success) ? $row : [];
    }

    public function getByCategoryId($table, $column)
    {
        $stm = $this->pdo->prepare('SELECT * FROM ' . $table . ' WHERE name =:column');
        $stm->bindValue(':column', $column);
        $success = $stm->execute();
        $row = $stm->fetch(PDO::FETCH_ASSOC);
       //  print_r($row);
        return ($success) ? $row : [];
    }

 


    // For Dashboard
    // public function incomeTransition()
    // {
    //    try{

    //        $sql        = "SELECT *,SUM(amount) AS amount FROM incomes WHERE
    //        (date = { fn CURDATE() }) ";
    //        $stm = $this->pdo->prepare($sql);
    //        $success = $stm->execute();

    //        $row     = $stm->fetch(PDO::FETCH_ASSOC);
    //        return ($success) ? $row : [];

    //     }
    //     catch( Exception $e)
    //     {
    //         echo($e);
    //     }
     
    // }

    public function incomeTransition()
    {
        try {
            $sql = "SELECT SUM(amount) AS total_amount FROM incomes WHERE date = CURDATE()";
            $stm = $this->pdo->prepare($sql);
            $success = $stm->execute();
            $row = $stm->fetch(PDO::FETCH_ASSOC);
            return ($success) ? $row : [];
        } catch (Exception $e) {
            echo($e);
        }
    }
        public function deletedoc($table, $value, $column = 'id')
    {
        $sql = "DELETE FROM {$table} WHERE {$column} = :value";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':value', $value);
        return $stmt->execute();
    }



    // public function expenseTransition()
    // {
    //    try{

    //        $sql        = "SELECT * ,SUM(amount*qty) AS amount FROM expenses WHERE
    //        (date = { fn CURDATE() }) ";
    //        $stm = $this->pdo->prepare($sql);
    //        $success = $stm->execute();

    //        $row     = $stm->fetch(PDO::FETCH_ASSOC);
    //        return ($success) ? $row : [];

    //     }
    //     catch( Exception $e)
    //     {
    //         echo($e);
    //     }
     
    // }
    public function expenseTransition()
    {
        try {
            $sql = "SELECT SUM(amount * qty) AS total_expense FROM expenses WHERE date = CURDATE()";
            $stm = $this->pdo->prepare($sql);
            $success = $stm->execute();
            $row = $stm->fetch(PDO::FETCH_ASSOC);
            return ($success) ? $row : [];
        } catch (Exception $e) {
            echo($e);
        }
    }
    public function callProcedure($procedureName, $params = [])
    {
        $placeholders = implode(',', array_fill(0, count($params), '?'));
        $stmt = $this->pdo->prepare("CALL $procedureName($placeholders)");
        return $stmt->execute($params);
    }
    
   public function adddoctor($procedureName, $params = [])
    {
        $placeholders = implode(',', array_fill(0, count($params), '?'));
        $sql = "CALL {$procedureName}({$placeholders})";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        // Fetch the first row (assumes your stored procedure SELECTs new_user_id AS user_id)
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt->closeCursor(); // important for MySQL procedures

        return $result['user_id'] ?? 0;
    }




}

