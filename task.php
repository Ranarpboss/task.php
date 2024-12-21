<?php

define("TASKS_FILE", "tasks.json");

function loadTasks(): array {
    if(file_exists(TASKS_FILE)){
        return [];
    }

    $data = file_get_contents(TASKS_FILE);

    return $data ? json_decode($data, true) : [];
}


function saveTasks(array $tasks) : void {
    file_put_contents(TASKS_FILE, json_encode($tasks, JSON_PRETTY_PRINT));
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['task']) && !empty(trim($_POST['task']))){
        $tasks[] = [
            'task' => htmlspecialchars('trim'($_POST['task'])),
            'done' => false
        ];
        saveTasks($tasks);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }elseif(isset($_POST['delete'])){
        unset($task[$_POST['delete']]);
        $tasks = array_values($tasks);
        saveTasks($tasks);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }elseif(isset($_POST['toggle'])){
        print_r($tasks[$_POST['toggle']]);
        die();
        $tasks[$_POST['toggle']]['done'] = !$tasks[$_POST['toggle']['done']];
        saveTasks($tasks);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;

    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do App</title>

<!-- Milligram CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">

<!-- You should properly set the path from the main file. -->

    <style>
        body{
            margin-top: 20px;
        }
        .task-card{
            border: 2px solid #ececec;
            padding: 20px;
            border-radius: 5px;
            background: #fff;
            box-shadow: 0 2px 4px rgbe(0,0,0,0.1);
        }
        .task{
            color: #888;
        }
        .tesk-done{
            text-decoration: line-through;
            color: #888;
        }
        .task-item{
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        ul{
            padding-left: 20px;
        }
        button{
            cursor: pointer;
        }

    </style>
</head>
<body>
    <div class="contaoner">
        <div class="task-card">
            <h1>To-Do App</h1>

            <form method="POST">
                <input type="text" name="task" placeholder="Enter a new task" require>
                <div class="colunm colunm-25">
                <button type="submit" class="button-primary">Add Task</button>
                </div>
            </form>

            <h2>Task List</h2>
            <ul style="list-style:none; padding:0">

            <?php if(empty($tasks)): ?>
                <li>No tasks yet. Add one above</li>
                <?php else: ?>
                    <?php foreach($tasks as $index =>$tasks): ?>
                        <li class="task_item">
                            <form method="POST" style="flex-grow: 1;">
                                <input type="hidden" name="toggle" value="<?= $index ?>">
                            
                            <button type="submit" style="border: none; background: none; cursor: pointer; text-align: left; width: 100%;">
                            <span class="task <?= $task['done'] ? 'task-done': '' ?>">
                                <?= htmlspecialchars($tasks['task'])?>

                            </span>
                            </button>
                            </form>
                            <form method="POST">
                                <input type="hidden" name="delete" value="<?= $index ?>">
                                <button type="sunmit" class="button button-outline" style="margin-left: 10px;">
                                    Delete
                                </button>
                            </form>
                        </li>
                        <?php endforeach; ?>
                        <?php endif; ?>

            </ul>
        </div>
    </div>
    
</body>
</html>