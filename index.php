<?php
session_start();
require 'db.php';

if(!isset($_SESSION['user'])){
    header('Location: login.php');
    exit;
}

// Add new task
if(isset($_POST['add'])){
    $title = $_POST['title'];
    $description = $_POST['description'];
    $stmt = $pdo->prepare("INSERT INTO tasks (title, description) VALUES (?, ?)");
    $stmt->execute([$title, $description]);
}

// Mark task as completed
if(isset($_GET['complete'])){
    $id = $_GET['complete'];
    $stmt = $pdo->prepare("UPDATE tasks SET status='Completed' WHERE id=?");
    $stmt->execute([$id]);
}

// Delete task
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id=?");
    $stmt->execute([$id]);
}

// Fetch all tasks
$stmt = $pdo->query("SELECT * FROM tasks ORDER BY id DESC");
$tasks = $stmt->fetchAll();
?>

<link rel="stylesheet" href="style.css">
<div class="container">
    <h2>Task Manager Dashboard</h2>
    <a href="logout.php" class="logout">Logout</a>

    <h3>Add New Task</h3>
    <form method="post">
        <input type="text" name="title" placeholder="Task Title" required>
        <textarea name="description" placeholder="Task Description"></textarea>
        <button type="submit" name="add">Add Task</button>
    </form>

    <h3>Your Tasks</h3>
    <table>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach($tasks as $task): ?>
        <tr>
            <td><?php echo htmlspecialchars($task['title']); ?></td>
            <td><?php echo htmlspecialchars($task['description']); ?></td>
            <td class="<?php echo $task['status']=='Completed' ? 'status-completed':'status-pending'; ?>">
                <?php echo $task['status']; ?>
            </td>
            <td>
                <?php if($task['status']=='Pending'): ?>
                    <a href="?complete=<?php echo $task['id']; ?>" class="action-btn complete-btn">Complete</a>
                <?php endif; ?>
                <a href="?delete=<?php echo $task['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
