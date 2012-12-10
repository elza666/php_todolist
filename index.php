<?php
require_once __DIR__ . '/../../Nette/Nette/loader.php';
Nette\Diagnostics\Debugger::enable();
?>


<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <title>ToDo list</title>
</head>
<body>

   <h1>Úkolovník</h1>



<?php

$config = require_once __DIR__ . '/config.php';

    // pripojeni do databaze
    $connection = new PDO(
       'mysql:host=localhost;dbname=todolist',
       $config['database']['username'],
       $config['database']['password']

        );

require_once __DIR__ . '/TodoRepository.php';

    // vytvoreni instance tridy TodoRepository
    $todoRepository = new TodoRepository($connection);
    // volani metody
    $todos = $todoRepository->getAllTodos();

?>

    <!-- vypis do tabulky -->

    <?php
    if (count($todos) > 0)
    {?>
    <table>
        <tr>
            <th>Seznam úkolů</th>

        </tr>
        <?php foreach ($todos as $todo)

        {
        
        echo '<tr>' . 
            '<td '; if ($todo['done'] == TRUE) {echo 'class="finished"';} else {echo 'class="unfinished"';} echo '>';

                // kdyz ukol splneny
                if($todo['done'] == TRUE)

                {?>
                    <a href="index.php?id=<?php echo $todo['id']; ?>"><?php echo $todo['content'];?></a>
                <?php 
                }


                 // jinak vypis nesplneny ukol
                else
                {?>
                    <a href="index.php?id=<?php echo $todo['id']; ?>"><?php echo $todo['content']; ?></a>
                <?php
                }

                if (isset($_GET['id']))
                    {
                        $index = $_GET['id'];

                        if($todo['done'] == TRUE)
                        {
                            $todoRepository->getUnDone($index);    
                        }
                        else
                        {
                            $todoRepository->getDone($index);
                        }
                      
                        
                        header ("Location: ./");
                    }



            echo '</td>' . '</tr>';
        
        }
        ?>
    
    </table>
    <?php
    }
    ?>


</body>
</html>