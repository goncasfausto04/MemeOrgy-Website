<?php
// Connect to the database
$servername = "localhost"; // Update with your DB details
$username = "root";
$password = "";
$dbname = "fumblemeterdb"; // Database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get all users and their stats
$sql = "SELECT name, fumbles, goals_stolen, good_plays FROM counters";
$result = $conn->query($sql);

$users = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Increment the stats when a button is pressed
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['stat'])) {
    $name = $_POST["name"];
    $stat = $_POST["stat"];

    $sql = "UPDATE counters SET $stat = $stat + 1 WHERE name = '$name'";
    $conn->query($sql);
    header("Location: index.php"); // Avoid form re-submission
    exit();
}

// Add new person to the database
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_person'])) {
    $new_name = $_POST['new_person'];

    // Insert the new person into the database with default 0 values for the stats
    $sql = "INSERT INTO counters (name, fumbles, goals_stolen, good_plays) VALUES ('$new_name', 0, 0, 0)";
    $conn->query($sql);
    header("Location: index.php"); // Avoid form re-submission
    exit();
}

// Remove a person from the database
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_name'])) {
    $remove_name = $_POST['remove_name'];

    // Delete the person from the database
    $sql = "DELETE FROM counters WHERE name = '$remove_name'";
    $conn->query($sql);
    header("Location: index.php"); // Avoid form re-submission
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Counter Website</title>
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <header>
        <nav>
            <ul class="left">
                <li><a href="#"><i class="fas fa-home"></i></a></li>
            </ul>
            <ul class="right">
                <li><a href="#" class="active" id="rocket-league-meter-link">Rocket League Meter</a></li>
                <li><a href="#" id="casino-link">Casino</a></li>
                <li><a href="#" id="about-us-link">About Us</a></li>
            </ul>
        </nav>
    </header>

    <h1>Fumblemeter</h1>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Fumbles</th>
                    <th>Goals Stolen</th>
                    <th>Good Plays</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['fumbles']); ?></td>
                        <td><?php echo htmlspecialchars($user['goals_stolen']); ?></td>
                        <td><?php echo htmlspecialchars($user['good_plays']); ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="name" value="<?php echo htmlspecialchars($user['name']); ?>">
                                <input type="hidden" name="stat" value="fumbles">
                                <button type="submit">Fumble</button>
                            </form>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="name" value="<?php echo htmlspecialchars($user['name']); ?>">
                                <input type="hidden" name="stat" value="goals_stolen">
                                <button type="submit">Goal Stolen</button>
                            </form>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="name" value="<?php echo htmlspecialchars($user['name']); ?>">
                                <input type="hidden" name="stat" value="good_plays">
                                <button type="submit">Good Play</button>
                            </form>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="remove_name"
                                    value="<?php echo htmlspecialchars($user['name']); ?>">
                                <button type="submit" id="removebtn">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <h2>Add a New Person</h2>
    <form method="post">
        <label for="new_person" style="color: white;">Enter Name: </label>
        <input type="text" name="new_person" id="new_person" required>
        <button type="submit">Add Person</button>
    </form>

</body>
<footer>
    <div class="footer-content">
        <h>Meme Orgy</h4>
        <p>&copy; 2023 Meme Orgy. All rights reserved.</p>
    </div>
</footer>
</html>