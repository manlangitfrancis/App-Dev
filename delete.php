<?php
require_once "database.php"; 

if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id = trim($_GET["id"]);

    $sql = "DELETE FROM products WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if ($stmt->execute()) {
                header("Location: index.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        $stmt->close();
    } else {
        echo "Failed to prepare the SQL statement.";
    }

    $conn->close();
} else {
    header("Location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete</title>
</head>

<body>
  <h2>Delete</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . htmlspecialchars($_GET["id"]); ?>" method="post">
    <div>
      <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET["id"]); ?>" />
      <p>Delete?</p>
      <p>
        <input type="submit" value="Yes">
        <a href="index.php">No</a>
      </p>
    </div>
  </form>
</body>

</html>
