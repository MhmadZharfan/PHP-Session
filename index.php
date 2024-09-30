<?php
session_start();


if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_task'])) {
    $task = [
        'id' => uniqid(),
        'name' => $_POST['name'],
        'priority' => $_POST['priority'],
        'status' => 'Pending'
    ];
    $_SESSION['tasks'][] = $task;
}


if (isset($_GET['delete'])) {
    foreach ($_SESSION['tasks'] as $key => $task) {
        if ($task['id'] == $_GET['delete']) {
            unset($_SESSION['tasks'][$key]);
        }
    }
    $_SESSION['tasks'] = array_values($_SESSION['tasks']); // Reindex array
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_task'])) {
    foreach ($_SESSION['tasks'] as &$task) {
        if ($task['id'] == $_POST['id']) {
            $task['name'] = $_POST['name'];
            $task['priority'] = $_POST['priority'];
            break;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List with PHP Sessions</title>
</head>
<body>
    <h1>To-Do List</h1>

    <h2>Add Task</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Task Name" required>
        <select name="priority" required>
            <option value="Low">Low</option>
            <option value="Medium">Medium</option>
            <option value="High">High</option>
        </select>
        <button type="submit" name="add_task">Add Task</button>
    </form>

    <h2>Task List</h2>
    <?php if (!empty($_SESSION['tasks'])): ?>
        <ul>
        <?php foreach ($_SESSION['tasks'] as $task): ?>
            <li>
                <strong><?php echo $task['name']; ?></strong> - Priority: <?php echo $task['priority']; ?>
                <a href="?edit=<?php echo $task['id']; ?>">Edit</a>
                <a href="?delete=<?php echo $task['id']; ?>">Delete</a>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No tasks added yet.</p>
    <?php endif; ?>

    <?php if (isset($_GET['edit'])): ?>
        <?php
        $taskToEdit = null;
        foreach ($_SESSION['tasks'] as $task) {
            if ($task['id'] == $_GET['edit']) {
                $taskToEdit = $task;
                break;
            }
        }
        if ($taskToEdit):
        ?>
        <h2>Edit Task</h2>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $taskToEdit['id']; ?>">
            <input type="text" name="name" value="<?php echo $taskToEdit['name']; ?>" required>
            <select name="priority" required>
                <option value="Low" <?php if ($taskToEdit['priority'] == 'Low') echo 'selected'; ?>>Low</option>
                <option value="Medium" <?php if ($taskToEdit['priority'] == 'Medium') echo 'selected'; ?>>Medium</option>
                <option value="High" <?php if ($taskToEdit['priority'] == 'High') echo 'selected'; ?>>High</option>
            </select>
            <button type="submit" name="edit_task">Update Task</button>
        </form>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
