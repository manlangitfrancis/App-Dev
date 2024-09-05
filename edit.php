<?php
require_once "database.php";

$name_err = $description_err = $price_err = $quantity_err = ""; // Initialize error variables

if (isset($_POST["id"]) && !empty($_POST["id"])) {
    $id = trim($_POST["id"]);

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    if (empty($name)) {
        $name_err = "Please enter a name.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $name_err = "Please enter a valid name.";
    }

    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    if (empty($description)) {
        $description_err = "Please enter a description.";
    }

    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_INT);
    if (empty($price)) {
        $price_err = "Please enter the amount.";
    } elseif (!ctype_digit($price)) {
        $price_err = "Please enter a valid positive integer value for price.";
    }

    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);
    if (empty($quantity)) {
        $quantity_err = "Please enter the quantity.";
    } elseif (!ctype_digit($quantity)) {
        $quantity_err = "Please enter a valid quantity.";
    }

    if (empty($name_err) && empty($description_err) && empty($price_err) && empty($quantity_err)) {
        $sql = "UPDATE products SET name=?, description=?, price=?, quantity=? WHERE id=?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssssi", $name, $description, $price, $quantity, $id);

            if ($stmt->execute()) {
                header("Location: index.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            $stmt->close();
        } else {
            echo "Failed to prepare the SQL statement.";
        }

        $conn->close();
    }
} else {
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        $id = trim($_GET["id"]);

        $sql = "SELECT * FROM products WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                $result = $stmt->get_result();

                if ($result->num_rows === 1) {
                    $row = $result->fetch_assoc();

                    $name = $row["name"];
                    $description = $row["description"];
                    $price = $row["price"];
                    $quantity = $row["quantity"];
                } else {
                    header("Location: error.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
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
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Edit Product</title>
</head>

<body>
  <h2>Edit</h2>
  <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . htmlspecialchars($id); ?>" method="post">
    <label>Name</label>
    <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
    <label>Description</label>
    <input type="text" name="description" value="<?php echo htmlspecialchars($description); ?>">
    <label>Price</label>
    <input type="text" name="price" value="<?php echo htmlspecialchars($price); ?>">
    <label>Quantity</label>
    <input type="text" name="quantity" value="<?php echo htmlspecialchars($quantity); ?>">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
    <input type="submit" value="Submit">
    <a href="index.php">Cancel</a>
  </form>
</body>

</html>
