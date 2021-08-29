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

    $searchinfos = $connection->prepare("SELECT cod, nome, email, cpf, telefone, plano, image_user FROM userstableapplication WHERE email = :email LIMIT 1");
    $searchinfos->bindParam(':email', $_SESSION['user_email']);

    $searchinfos->execute();

    if ($searchinfos->rowCount() > 0) {

        $row = $searchinfos->fetchAll(PDO::FETCH_ASSOC);

        foreach ($row as $getdata) {
            $user_cod      =   $getdata['cod'];
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


try {

    $serchEvents = $connection->prepare("SELECT id, title, color, start, end FROM eventstableapplicartion WHERE coduser =" . $user_cod);
    $serchEvents->execute();

    $rowEvents = $serchEvents->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $error) {
    die('Erro Ao Tentar Se Comunicar com o Servidor, Tente Novamente Mais Tarde.');
}


if (isset($_SESSION['Msg_error'])) {
    echo "<script>alert('Não Foi Possivel Cadastrar o Evento!')</script>";
    unset($_SESSION['Msg_error']);
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
    <link rel="stylesheet" href="../../../../source/styles/dashboard/calendar/main.css">
    <!-- <link rel="stylesheet" href="../../source/styles/mobile/main.css"> -->

    <!-- Mask Input JS -->
    <script src="https://cdn.jsdelivr.net/gh/miguelhp373/MaskInputJS/maskjs@1.3/maskjs.min.js"></script>

    <!-- Calendar JS -->
    <script src="lib/calendarjs/main.min.js"></script>
    <link rel="stylesheet" href="lib/calendarjs/main.min.css">
    <script src="lib/calendarjs/locales-all.min.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var idEventURL = ''
            var getDatClicked = ''
            var dateini = ''
            var datefim = ''
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                displayEventTime: true,
                timeZone: 'local',
                locale: 'pt-br',
                defaultDate: Date(),
                navLinks: true, // can click day/week names to navigate views
                selectable: true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,listYear'
                },
                dateClick: function(info) {
                    $(".popup_filter").removeClass("hidden");
                    $('#dateini').prop('value', info.dateStr)
                    $('#datefim').prop('value', info.dateStr)
                },
                eventClick: function(info) {
                    $(".popup_delete").removeClass("hidden");
                    info.jsEvent.preventDefault(); // don't let the browser navigate
                    //console.log(info.event.start.toLocaleString()    
                    var dateini = info.event.start.toLocaleString().substr(0, 10)

                    $('#date_input_edit_ini').val(dateini.split('/').reverse().join('-'));
                    $('#title_event_edit').val(info.event.title);
                    $('#color_picker_edit').val(info.event.backgroundColor);
                    $('#id_event').val(info.event.id);

                    idEventURL = info.event.id;


                },
                events: [
                    <?php foreach ($rowEvents as $getevents) { ?> {
                            id: "<?php printf($getevents['id']) ?>",
                            title: "<?php printf($getevents['title']) ?>",
                            start: "<?php printf($getevents['start']) ?>",
                            end: "<?php printf($getevents['end']) ?>",
                            color: "<?php printf($getevents['color']) ?>",
                        },
                    <?php  } ?>
                ]
            });
            calendar.render();

            $('#btn_delete').click(function(){
                window.location.href = `model/DeleteEvent.php?id=${idEventURL}`
            })
        });
    </script>

    <style>
        #calendar {
            max-width: 1100px;
            width: 923px;
            margin: 40px auto;
            padding: 0 10px;
        }

        .fc-event-time,
        .fc-list-event-time {
            display: none;
        }

        .fc-daygrid-day {
            cursor: pointer;
        }
    </style>

    <script>
        $(function() {
            $("#close_pop_up").click(function() {
                $(".popup_filter").addClass("hidden");
            });

            $("#close_pop_delete").click(function() {
                $(".popup_delete").addClass("hidden");

            });

    

        });
    </script>


</head>

<body>
    <div class="container_page">
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
                            <h1>Novo Evento</h1>
                        </div>
                        <form action="model/main.php" method="post">
                            <br>

                            <div class="col_dates">
                                <label for="date_input" class="lb_dates">
                                    Data Inicial:
                                    &nbsp;&nbsp;
                                    <input type="date" name="dateini" class="date_input" maxlength="9" id="dateini">
                                </label>
                            </div>
                            <div class="row_title">
                                <label for="title_event">
                                    Descrição do Evento
                                    <input type="text" name="title" id="" class="title_event">
                                </label>
                            </div>
                            <br>
                            <div class="row_color">
                                <label for="color_picker">
                                    Escolha a Cor:
                                    <input type="color" name="color_picker" id="" class="color_picker">
                                </label>
                            </div>


                            <div class="row_btn_submit">
                                <button type="submit">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!---FIM POPUP-->

        <!--POPUP delete-->
        <div class="popup_delete hidden">
            <div class="row_content">
                <div class="col_button_popup_close">
                    <button id="close_pop_delete" class="close_pop_up">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="column_content">

                    <div class="content">
                        <br>
                        <div class="title_popup">
                            <h1>Editar Evento</h1>
                        </div>
                        <form action="model/UpdateEvent.php" method="post">
                            <br>
                            <input type="text" style="display: none;" id="id_event" name="id_event">
                            <div class="col_dates">
                                <label for="date_input" class="lb_dates">
                                    Data Inicial:
                                    &nbsp;&nbsp;
                                    <input type="date" name="dateini" class="date_input" id="date_input_edit_ini" maxlength="9">
                                </label>
                            </div>
                            <div class="row_title">
                                <label for="title_event">
                                    Descrição do Evento
                                    <input type="text" name="title" class="title_event" id="title_event_edit">
                                </label>
                            </div>
                            <br>
                            <div class="row_color">
                                <label for="color_picker">
                                    Escolha a Cor:
                                    <input type="color" name="color_picker" class="color_picker" id="color_picker_edit">
                                </label>
                            </div>


                            <div class="row_btn_submit">
                                <button type="submit">Salvar</button>
                            </div>
                        </form>
                        <div class="row_btn_delete">
                                <button id="btn_delete">Apagar</button>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!---FIM POPUP 02-->

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
        <div id='calendar'>

        </div>

    </div>

</body>

</html>