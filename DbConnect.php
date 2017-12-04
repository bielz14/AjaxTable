<?php

class DbConnect
{   
    public $connect;

    public function __construct($host_par, $db_name_par, $login_par, $pswrd_par)
    {  
        $host = $host_par;
        $db_name = $db_name_par;
        $login = $login_par;
        $pswrd = $pswrd_par;

        $mysqli = new mysqli("$host", "$login", "$pswrd", "$db_name") or die ("No connection MySQL"); 

        /* Проверка подключения */ 
        if ($mysqli->connect_errno) {
            printf("Ошибка подключения: %s\n", mysqli_connect_error());
            exit();
        }
        else { 

            $this->connect = $mysqli;
        }
    }

    public function getConnection()
    {
        return $this->connect;
    }

    public function closeDb()
    {
        mysqli_close($mysqli);
        mysqli_close($connect);
    }
}

?>