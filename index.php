<?php
require_once __DIR__ . '/../../Nette/Nette/loader.php';
Nette\Diagnostics\Debugger::enable();

$config = require_once __DIR__ . '/config.php';

require_once __DIR__ . '/TodoRepository.php';

    // pripojeni do databaze
    $connection = new PDO(
       'mysql:host=localhost;dbname=todolist',
       $config['database']['username'],
       $config['database']['password'],
       // vyhodi chybu, kdyz se nepodari navazat spojeni s databazi
       array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    )

        );


    // vytvoreni instance tridy TodoRepository
    $todoRepository = new TodoRepository($connection);


    // volani metody oznaceni ukolu jako hotoveho
    if (isset($_GET['finish'])) 
    {
        try {
            $todoRepository->getDone($_GET['finish']);
                header('Location: ./');
                exit;
            } catch (UnsuccessfulFinishException $e) {
                    echo 'Dokončení úkolu se nepovedlo.';
            }
    }

    // volani metody, ktera ukol znovu oznaci jako nehotovy
    if (isset($_GET['unfinish'])) 
    {
        $todoRepository->getUnDone($_GET['unfinish']);
            header('Location: ./');
            exit;
    }    

    // volani metody pridani noveho todocka
    if (isset($_POST['send']))
    {
        try {
                $todoRepository->create($_POST['content']);
                    header('Location: ./');
                    exit;
            } catch (UnsuccessfulCreateException $e) {
                    echo 'Přidání nového úkolu se nepovedlo.';
            }
    }

    // volani metody smazani jednoho todocka
    if (isset($_GET['delete']))
    {
        $todoRepository->delete($_GET['delete']);
        header('Location: ./');
        exit;
    }

?>


<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <title>ToDo list</title>
</head>
<body>
    <div id="horniPruh">
    <div class="vnitrni">

    <h1>Úkolovník</h1>

    </div>
    </div>

    <div id="dolniPruh">
    <div class="vnitrni">
    
    
    
    </div>
    </div>

    <div id="hlavniObsah">
    <div class="vnitrni">


        
        <form method="post">
            <label for="content">Nový úkol:</label>
            <input type="text" name="content" id="content">
            <input type="submit" name="send" value="Přidat">
        </form>
        


<?php


    // volani metody
    $todos = $todoRepository->getAllTodos();

?>

    <!-- vypis do tabulky -->
<div align="center">
    <?php
if (count($todos) > 0) {
    echo '<table>';
    foreach ($todos as $todo) {
        echo '<tr>'
        . '<td'; if ($todo['done']) { echo ' class="finished"'; } echo '>'
        . $todo['content'] . '</td><td>';
        if (!$todo['done']) {
            echo '<a href="?finish=' . $todo['id'] . '"><img src="images/done.png" width="20" height="20" alt="Vyřídit" title="Vyřídit"></a>';
        } else {
            echo '<a href="?unfinish=' . $todo['id'] . '"><img src="images/reload.png" width="20" height="20" alt="Odznačit" title="Odznačit"></a>&nbsp;<a href="?delete=' . $todo['id'] . '"><img src="images/delete.png" width="20" height="20" alt="Smazat" title="Smazat"></a>';
        }
        echo '</td></tr>';
    }
    echo '</table>';
}
        ?>
</div>
    </div>
    </div>
</body>
</html>