<?php
include('config.php');
include('todolist.php');

$app = new TodoList(date('Ymd'));
$todolist = $app->getTodoes();


$reqMethod = $_SERVER['REQUEST_METHOD'];


switch ($reqMethod) {
    case 'POST':
        if ($_POST['action'] === 'update') {


            $task=$_POST['task'];
            $note=$_POST['note'];
            $priority=$_POST['priority'];
            echo $task." ".$note." ".$priority;

            $app->update($_POST['id']);
        }
        else if($_POST['action'] === 'insert')
            $app->add();
        break;
    case 'GET':
        if ($_GET['action'] === 'delete' && !empty($_GET['id'])) {
            $app->delete($_GET['id']);
            break;
        } elseif ($_GET['action'] === 'status' && !empty($_GET['id'])) {
            $app->statusChange($_GET['id']);
            break;
        }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>


    <link href="assets/bootstrap/css/simple-sidebar.css" rel="stylesheet"/>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="assets/fontawsome/css/all.min.css" rel="stylesheet"/>


    <style>
        th {
            cursor: pointer;
        }

    </style>


</head>

<body>

<div class="container mt-5">
    <h3>Göerevler</h3>
    <div id="toolbar" class=" mt-2">
        <button class="btn btn-primary mt-2"  data-toggle="modal" data-target="#adding-modal">
            <i class="fas fa-plus"></i> Ekle
        </button>
    </div>
    <div class="row mt-2">
        <div class="col-md-12">
            <table id="myTable" class="table table-borderless" id="myTable">
                <thead>
                <tr>
                    <th onclick="sortTable(0)"># <i class="fas fa-sort"></i></th>
                    <th onclick="sortTable(1)">Tanim <i class="fas fa-sort"></i></th>
                    <th onclick="sortTable(2)">Açıklama <i class="fas fa-sort"></i></th>
                    <th onclick="sortTable(3)">Aciliyet <i class="fas fa-sort"></i></th>
                    <th onclick="sortTable(4)">Durum <i class="fas fa-sort"></i></th>
                    <th onclick="sortTable(5)">Tarih <i class="fas fa-sort"></i></th>
                    <th>işlem</th>

                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($todolist as $key => $value) {


                    echo ' <tr> <td scope="row">    ' . $key . ' <a   class="open-homeEvents" data-id="' . $key . '"  data-toggle="modal" data-target="#update-modal"   />  <i class="fas fa-edit"></i></a></td>  <td> ' . $value->task . '</td><td>' . $value->note . '</td> <td>' . $value->priority . '</td> <td>';
                    echo ($value->status == true ? "tamamlandı" : "tamamlanmadı") . ' </td><td>' . $value->dateTime . '</td>  <td>';


                    if (!$value->status)
                        echo '<a  data-toggle="modal"   data-target="#completeModal"   data-taskid="' . $key . '"  class="btn btn-primary btn-sm " > <i class="fas fa-check mr-2" ></i> Tamamla</a>';
                    else
                        echo ' <a data-toggle="modal" data-target="#DeleteModal" data-taskid="' . $key . '" class="btn btn-danger btn-sm mr-1"> <i class="far fa-trash-alt mr-2"></i>Sil</a>';
                    //  continue;

                }
                echo "</td></tr>";

                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!--   Göreve  bitirme Modalı   -->

<div class="modal fade" id="completeModal" tabindex="-1" role="dialog" aria-labelledby="completeModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="completeModalLabel">UYARI</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Görevin bittiğini onaylıyor musunz ?
            </div>
            <div class="modal-footer">
                <a type="button" class="btn btn-secondary" data-dismiss="modal">Hayır </a>


                <button id="completeConfirmed" type="button" class="btn btn-primary">Evet</button>

            </div>
        </div>
    </div>
</div>


<!--   Göreve  silme Modalı   -->
<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">UYARI</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Silmek istediğinizden emin misiniz?
            </div>
            <div class="modal-footer">
                <a type="button" class="btn btn-secondary" data-dismiss="modal">Hayır Vazgeç</a>


                <button id="DelConfirmed" type="button" class="btn btn-primary">Evet Sil</button>

            </div>
        </div>
    </div>
</div>

<!--   Göreve  ekleme Modal'ı   -->

<div id="adding-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Göreve ekleme Formu</h3>

                <a class="close" data-dismiss="modal" style="cursor: pointer">×</a>
            </div>

            <form action="index.php" method="post" id="contactForm" name="contact" role="form">
                <input type="hidden" name="action" value="insert">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="task">Tanım</label>
                        <input type="text" name="task" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="note">Açıklama</label>
                        <textarea name="note" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="priority">Aciliyet</label>
                        <select class="form-control" name="priority" id="priority">
                            <option value="Çok Acil">Çok Acil</option>
                            <option value="Acil">Acil</option>
                            <option value="Ertelenebilir">Ertelenebilir</option>
                        </select>

                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-success" id="submit">
                </div>
            </form>
        </div>
    </div>
</div>


<!--   Göreve  Güncelleme Modal'ı   -->
<div id="update-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Görev Güncellme Formu</h3>

                <a class="close" data-dismiss="modal" style="cursor: pointer">×</a>
            </div>

            <form action="index.php" method="post" id="updateform" name="contact" role="form">
                <input type="hidden" name="action" value="update">

                <?php

                //  print_r($app->findById(1));     ?>




                <input id="uptedid" type="hidden" name="id" value=""/>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="task">Tanım</label>
                        <input type="text" name="task" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="note">Açıklama</label>
                        <textarea   name="note" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="priority">Aciliyet</label>
                        <select class="form-control" name="priority" id="priority" >
                            <option value="Çok Acil">Çok Acil</option>
                            <option value="Acil">Acil</option>
                            <option value="Ertelenebilir">Ertelenebilir</option>
                        </select>


                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input id="uptedpost" type="submit" class="btn btn-success" id="submit">
                </div>
            </form>
        </div>
    </div>
</div>


<script src="assets/jquery/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/fontawsome/js/all.min.js"></script>


<script type="text/javascript">

     //  Göreve  bitirme onayı
    $(document).ready(function () {
        $("#completeModal").on("show.bs.modal", function (event) {
            let triggeredElement = $(event.relatedTarget);
            let id = triggeredElement.data("taskid");
            // console.log(id);
            $("#completeConfirmed").click(function () {
                $.ajax({
                    type: "GET",
                    data: {
                        action: 'status',
                        id: id + 1,
                    },
                    url: 'index.php',
                    success: function () {
                        //   alert(this.url);
                        window.location.reload();
                    },
                    error: function (error) {
                        alert(error.statusText);
                    }
                })
            });
        })
    });

     //  Göreve  silme onayı
    $(document).ready(function () {
        $("#DeleteModal").on("show.bs.modal", function (event) {
            let triggeredElement = $(event.relatedTarget);
            let id = triggeredElement.data("taskid");
            // console.log(id);
            $("#DelConfirmed").click(function () {
                $.ajax({
                    type: "GET",
                    data: {
                        action: 'delete',
                        id: id + 1,
                    },
                    url: 'index.php',
                    success: function () {
                        //   alert(this.url);
                        window.location.reload();
                    },
                    error: function (error) {
                        alert(error.statusText);
                    }
                })
            });
        })
    });
   //  Göreve  güncllme
     $(document).on("click", ".open-homeEvents", function () {
        var eventId = $(this).data('id');
        $('#uptedid').attr('value', eventId)
        $("#uptedpost").click(function () {
            $.ajax({
                type: "POST",
                data: {
                    action: 'update',
                    id: id ,
                },
                url: 'index.php',
                success: function () {
                    //   alert(this.url);
                    window.location.reload();
                },
                error: function (error) {
                    alert(error.statusText);
                }
            })
        });
    });

</script>



<script>
    function sortTable(n) {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById("myTable");
        switching = true;

        dir = "asc";

        while (switching) {

            switching = false;
            rows = table.rows;

            for (i = 1; i < (rows.length - 1); i++) {

                shouldSwitch = false;

                x = rows[i].getElementsByTagName("TD")[n];

                y = rows[i + 1].getElementsByTagName("TD")[n];


                if (dir == "asc") {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {

                        shouldSwitch = true;
                        break;
                    }
                } else if (dir == "desc") {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {

                        shouldSwitch = true;
                        break;
                    }
                }
            }
            if (shouldSwitch) {

                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;

                switchcount++;
            } else {

                if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
    }

</script>


</body>

</html>