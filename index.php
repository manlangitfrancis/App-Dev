<?php

require_once "database.php";

$sql = "SELECT id, name, description, price, quantity FROM products";

$result = mysqli_query($conn, $sql);


if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $productName = $_POST['name'];
  $productDescription = $_POST['description'];
  $productPrice = $_POST['price'];
  $productQuantity = $_POST['quantity'];


  $sql = "INSERT INTO products (name, description, price, quantity) VALUES (?, ?, ?, ?)";


  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ssii", $productName, $productDescription, $productPrice, $productQuantity);

    if ($stmt->execute()) {
      header("location: index.php");
      exit();
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <form action="create.php" method="post">
    <h1>Add</h1>
    <label for="name">Product Name</label>
    <input type="text" name="name" />
    <label for="description">Description</label>
    <input rows="1" type="text" name="description"></input>
    <label for="price">Price</label>
    <input type="text" name="price" />
    <label for="quantity">Quantity</label>
    <input type="text" name="quantity" />
    <input type="submit" value="Add Product" />
  </form>

  <br><br><br><br><br>

  <h1>Products</h1>
  <table>
    <tr>
      <th>Product</th>
      <th>Description</th>
      <th>Price</th>
      <th>Quantity</th>
      <th>Action</th>
    </tr>
    <?php
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
    ?>
        <tr>
          <td><?php echo $row['name'] ?></td>
          <td><?php echo $row['description'] ?></td>
          <td><?php echo $row['price'] ?></td>
          <td><?php echo $row['quantity'] ?></td>
          <td>
            <a href="edit.php?id=<?php echo $row['id'] ?>">Edit</a>
            <a href="delete.php?id=<?php echo $row['id'] ?>">Delete</a>
          </td>
        </tr>
    <?php
      }
    }
    ?>

  </table>
</body>

</html>