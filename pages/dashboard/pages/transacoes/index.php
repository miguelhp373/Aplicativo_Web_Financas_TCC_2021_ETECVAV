<?php

session_start();
require_once('../../../../source/controller/connection.php');

if (!isset($_SESSION['user_email']) || (!isset($_SESSION['Authentication']))) {
    if ($_SESSION['Authentication'] == '') {
        $_SESSION['Msg_error'] = 'Usuário Não Permitido!';
        header('Location: ../../../login/index.php');
    }
}

if (isset($_SESSION['Msg_error'])) {

    $_SESSION['Msg_error'] = '';
}

if (isset($_SESSION['Msg_sucess'])) {
    $_SESSION['Msg_sucess'] = '';
}


try {

    $searchinfos = $connection->prepare("SELECT nome, email, cpf, telefone, plano, image_user FROM userstableapplication WHERE email = :email LIMIT 1");
    $searchinfos->bindParam(':email', $_SESSION['user_email']);

    $searchinfos->execute();

    if ($searchinfos->rowCount() > 0) {

        $row = $searchinfos->fetchAll(PDO::FETCH_ASSOC);

        foreach ($row as $getdata) {
            $user_name      =   $getdata['nome'];
            $user_email     =   $getdata['email'];
            $user_cpf       =   $getdata['cpf'];
            $user_telefone  =   $getdata['telefone'];
            $user_plano     =   $getdata['plano'];
            $image_user     =   $getdata['image_user'];
        }
    }
} catch (PDOException $error) {
    die('Erro Ao Tentar Se Comunicar com o Servidor, Tente Novamente Mais Tarde.');
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Nome do App</title>

    <!--Jquery-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!--Bootstrap v5-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


    <!--Icones FontAwesome-->
    <script src="https://kit.fontawesome.com/bb41ae50aa.js" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="../../../../source/root/root.css">
    <link rel="stylesheet" href="../../../../source/styles/dashboard/transaction/main.css">
    <!-- <link rel="stylesheet" href="../../source/styles/mobile/main.css"> -->

    <!-- Mask Input JS -->
    <script src="https://cdn.jsdelivr.net/gh/miguelhp373/MaskInputJS/maskjs@1.3/maskjs.min.js"></script>

    <!-- Botão do Filtro Open e Close -->
    <script src="js/btn_filter.js"></script>
</head>

<body>
    <div class="container_page">
        <!--NavBar Desktop-->
        <div class="nav-bar-left-desktop">

            <div class="user_info">
                <div class="image_user_icon">
                    <img src="../../../../<?php echo $image_user; ?>" alt="">
                </div>
                <div class="text_name_user">
                    <span>
                        <?php
                        //nome do usuário pego pelo banco de dados
                        echo $user_name;
                        ?>
                    </span>
                </div>
            </div>

            <a href="../../index.php" class="link_menu">
                <i class="fas fa-home"></i>
                Home
            </a>

            <a href="../Minha conta/index.php" class="link_menu">
                <i class="fas fa-user-alt"></i>
                Minha Conta
            </a>

            <a href="#" class="link_menu">
                <i class="far fa-calendar-alt"></i>
                Calendário
            </a>

            <a href="#" class="link_menu">
                <i class="fas fa-pencil-ruler"></i>
                Dicas
            </a>

            <a href="#" class="link_menu">
                <i class="fas fa-question"></i>
                Ajuda
            </a>


            <a href="../../../login/index.php?login=logout" class="link_menu">
                <i class="fas fa-door-open"></i>
                Sair
            </a>

        </div>
        <!------------------>

        <div class="content_page">
            <div class="row_button_filter">
                <button id="btn_filter_show">
                    <i class="fas fa-filter"></i>
                </button>
            </div>
        </div>
        <!--POPUP-->
        <div class="popup_filter hidden">
            <div class="row_content">
                <div class="col_button_popup_close">
                    <button id="close_pop_up" class="close_pop_up">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="column_content">

                    <div class="content">
                        <br>
                        <div class="title_popup">
                            <h1>Filtro</h1>
                        </div>
                        <form action="" method="post">
                            <br>
                         
                            <div class="col_dates">
                            <label for="date_input" class="lb_dates">
                                Data Inicial
                                &nbsp;&nbsp;
                                <input type="date" name="dateini" class="date_input" maxlength="9">
                            </label>

                            <label for="date_input" class="lb_dates">
                                Data Final
                                &nbsp;&nbsp;
                                <input type="date" name="dateini" class="date_input" maxlength="9">
                            </label>
                            </div>
                            <div class="row_field_00">
                                <select name="" id="" class="sel_tip">
                                    <option value="null">Tipo de Transação</option>
                                    <option value="receita">Receitas</option>
                                    <option value="despesa">Despesas</option>
                                    <option value="todas">Todas</option>
                                </select>
                            </div>

                            <div class="row_btn_submit">
                                <button type="submit">Filtrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!---FIM POPUP-->

        <div class="container_content_grid">
            <!-- Aqui Vai ser o seu conteudo atilio -->
        </div>
    </div>


</body>

</html>