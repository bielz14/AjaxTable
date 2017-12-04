<?php

		$firstName = $_POST['inputFirstName'];
		$secondName = $_POST['inputSecondName'];
		$email = $_POST['inputEmail']; 

		require "DbConnect.php"; 

        $obj_db = new DbConnect('localhost', 'testing', 'root',   '1111');

        $connect = $obj_db->getConnection();

        if($_POST['upload']){

	        $query = "INSERT INTO `data` (`first name`, `second name`, `e-mail`) values ('$firstName', '$secondName', '$email')";

	        $res = mysqli_query($connect, $query);	

	        if($res)print_r($_POST);

    	}

        else if(isset($_POST['update'])){
        	$id = $_POST['id_row'];
        	$query = "UPDATE `data` SET `first name` = '$firstName', `second name` = '$secondName', `e-mail` = '$email' where `id` = '$id'";
        	$res = mysqli_query($connect, $query);

        	if($res)print_r($_POST);
        	
        }else {

        	 $id = $_POST['id_row'];

		     $query = "DELETE FROM `data` WHERE `id` = '$id'";

		     mysqli_query($connect, $query);
        }

?>