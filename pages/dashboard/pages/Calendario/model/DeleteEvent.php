<?php
    require_once('../../../../../source/controller/connection.php');
    session_start();
    
    if (!isset($_SESSION['user_email']) || (!isset($_SESSION['Authentication']))) {
        if ($_SESSION['Authentication'] == '') {
            $_SESSION['Msg_error'] = 'Usuário Não Permitido!';
            header('Location: ../../../../login/index.php');
            

        }
    }
    

    $id_Event = filter_input(INPUT_GET,'id',FILTER_SANITIZE_URL);


    try{
        $deleteEvent = $connection->prepare("DELETE FROM eventstableapplicartion WHERE id = :id");
        
        $deleteEvent->bindParam(':id',$id_Event );

        $deleteEvent->execute();
        
        if($deleteEvent->rowCount() > 0){
            header('Location: ../index.php');
        }else{
            $_SESSION['Msg_error']  =   "Erro ao Tentar Apagar Evento, Tente novamente mais tarde.";
            header('location: ../index.php');
        }
        
       
    
    }catch(PDOException $error){
        die('<br>Erro Ao Tentar se comunicar com o Servidor! Tente Novamente Mais Tarde');
    }


?>