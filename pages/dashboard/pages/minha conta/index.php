<?php

session_start();
require_once('../../../../source/controller/connection.php');

if (!isset($_SESSION['user_email']) || (!isset($_SESSION['Authentication']))) {
    if ($_SESSION['Authentication'] == '') {
        $_SESSION['Msg_error'] = 'Usuário Não Permitido!';
        header('Location: ../../../login/index.php');
    }
}

$index_options = ['plano01', 'plano02', 'plano03'];
$optionsValues = ['Pessoal', 'Familiar', 'Estudantil'];

if(isset($_GET['image'])){
    $_SESSION['image_selected'] = $_GET['image'];
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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Conta | Meu App</title>

    <!--Jquery-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!--Bootstrap v5-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


    <!--Icones FontAwesome-->
    <script src="https://kit.fontawesome.com/bb41ae50aa.js" crossorigin="anonymous"></script>


    <!-- Mask Input JS -->
    <script src="https://cdn.jsdelivr.net/gh/miguelhp373/MaskInputJS/maskjs@1.3/maskjs.min.js"></script>


    <link rel="stylesheet" href="../../../../source/root/root.css">
    <link rel="stylesheet" href="../../../../source/styles/dashboard/account_edit/main.css">
    <link rel="stylesheet" href="../../../../source/styles/mobile/dash_page/main.css">
</head>

<body class="body">
    <div class="select_image_pop_up hidden" id="select_image_pop_up">
        <div class="btn_close">
            <button id="close_pop_up">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="container">
            <h1>Selecione Seu Avatar</h1>
            <div class="row d-flex justify-content-between">
                <?php for ($count = 1; $count < 28; $count++) { ?>

                    <div class="image_select">
                        <a href="index.php?image=/source/assets/avatar_profiles/image_<?php echo $count ?>.png">
                            <img src="../../../../source/assets/avatar_profiles/image_<?php echo $count ?>.png" alt="">
                        </a>
                    </div>

                <?php   } ?>
            </div>
        </div>
    </div>
     <!--NavBar Mobile-->
  <div class="nav_bar_top_mobile">
    <nav class="navbar navbar-expand-lg navbar-light bg-light mobile">
      <div class="container-fluid">
        <a class="navbar-brand" href="../../index.php">Logo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <i class="fas fa-bars btn_menu"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#Info_section">O Que Somos?</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#planos_account">Nossos Planos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#form_contact">Fale Conosco</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../dicas/index.php">Dicas</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../login/index.php?page=login+from+create">Entrar</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </div>
  <!------------------>
    <div class="container_page">
        <div class="nav-bar-left-desktop">

            <div class="user_info">
                <div class="image_user_icon">
                    <img src="../../../..<?php echo $image_user;?>" alt="">
                </div>
                <div class="text_name_user">
                    <span>
                        <?php
                        echo $user_name;
                        ?>
                    </span>
                </div>
            </div>

            <a href="../../index.php" class="link_menu">
                <i class="fas fa-home"></i>
                Home
            </a>

            <a href="#" class="link_menu">
                <i class="fas fa-arrow-circle-up"></i>
                Receitas
            </a>
            <a href="#" class="link_menu">
                <i class="fas fa-arrow-circle-down"></i>
                Despesas
            </a>
            <a href="#" class="link_menu">
                <i class="far fa-calendar-alt"></i>
                Transações
            </a>

            <a href="../../../login/index.php?login=logout" class="link_menu">
                <i class="fas fa-door-open"></i>
                Sair
            </a>

        </div>
        <div class="content_page" id="content-page">
            <h1>Editar Conta</h1>

            <div class="profile_image">

                <div class="image">
                    <div class="edit_icon">
                        <i class="fas fa-edit" id="open_select_image"></i>
                    </div>
                    <?php if (isset($_GET['image'])) { ?>
                        <img src="../../../..<?php echo $_GET['image']; ?>" alt="imagem_perfil">
                    <?php } elseif(isset($image_user)) { ?>
                        <img src="../../../..<?php echo $image_user;?>" alt="imagem_perfil">
                    <?php }else{?>
                        <img src="" alt="Sem Imagem" srcset="">
                    <?php }?>
                    
                </div>
            </div>
            <div class="form-infos">
                <h2>Informações</h2>
                <?php if(isset($_SESSION['Msg_error'])){ ?>
                    <span class="text-danger d-flex justify-content-center">
                    <?php
                        echo  $_SESSION['Msg_error'];
                    ?>
                    </span>
                <?php }?>

                <?php if(isset($_SESSION['Msg_sucess'])){ ?>
                    <span class="text-success d-flex justify-content-center">
                    <?php
                        echo  $_SESSION['Msg_sucess'];
                    ?>
                    </span>
                <?php }?>

                    <form action="Model/main.php" method="post">

                    <div class="row-01">
                        <input type="text" placeholder="Nome" name='nome' value="<?php echo $user_name ?>">
                        <input type="email" placeholder="Email" name="email" value="<?php echo $user_email ?>">
                    </div>

                    <div class="row-02">
                        <input type="text" placeholder="Telefone" name="telefone" class='inputnumberphoneformat' value="<?php echo $user_telefone ?>">
                        <input type="text" placeholder="CPF" name="cpf" class="inputCpfformat" value="<?php echo $user_cpf ?>">
                    </div>

                    <div class="row-03">
                        <div class="field_pass">
                            <input type="password" name="senha" placeholder="Senha" id="pass_field">
                            <i class="fas fa-eye-slash" id="togglePassword01"></i>
                        </div>
                        <select name="typeAccount" id="typeAccount" class="typeAccount">
                            <option value="null">Escolha seu Plano</option>
                            <?php for ($i = 0; $i < 3; $i++) {
                                if ($index_options[$i] == $user_plano) {
                            ?>
                                    <option value="<?php echo $index_options[$i]; ?>" selected>
                                        <?php echo $optionsValues[$i]; ?>
                                    </option>

                                <?php } else { ?>
                                    <option value="<?php echo $index_options[$i]; ?>">
                                        <?php echo $optionsValues[$i]; ?>
                                    </option>
                            <?php }
                            } ?>

                        </select>
                    </div>

                    <div class="btn_save">
                        <button type="submit">
                            Salvar
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>


    <script>
        $('#togglePassword01').click(function() {

            if (($('#togglePassword01').attr('class') === 'fas fa-eye-slash') && ($('#pass_field').attr('type') === 'password')) {
                $('#pass_field').attr('type', 'text')
                $('#togglePassword01').removeClass('fa-eye-slash')
                $('#togglePassword01').addClass('fa-eye')
            } else {
                $('#pass_field').attr('type', 'password')
                $('#togglePassword01').removeClass('fa-eye')
                $('#togglePassword01').addClass('fa-eye-slash')

            }

        })

        $('#open_select_image').click(function() {
            $('.body').addClass('hidden_bar')
            $('.container_page').addClass('hidden')
            $('#select_image_pop_up').removeClass('hidden')
        })

        $('#close_pop_up').click(function() {
            $('#select_image_pop_up').addClass('hidden')
            $('.body').removeClass('hidden_bar')
            $('.container_page').removeClass('hidden')

        })
    </script>
</body>

</html>