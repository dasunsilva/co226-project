<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shopmart";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$customerID = $_POST['Customer_ID']; 
$itemID = $_POST['Item_ID'];
$itemName = $_POST['ItemName'];
$itemBrand = $_POST['ItemBrand'];
$itemPrice = $_POST['ItemPrice'];
$itemPhoto = $_POST['ItemPhoto'];
$itemQty = $_POST['ItemQty'];

$existingCartItemQuery = "SELECT * FROM cart WHERE Customer_ID = '$customerID' AND Item_ID = '$itemID'";
$existingCartItemResult = $conn->query($existingCartItemQuery);

if ($existingCartItemResult->num_rows > 0) {
    $existingCartItem = $existingCartItemResult->fetch_assoc();
    $updatedQty = $existingCartItem['ItemQty'] + 1;
    
    $updateQuery = "UPDATE cart SET ItemQty = '$updatedQty' WHERE Customer_ID = '$customerID' AND Item_ID = '$itemID'";
    
    if ($conn->query($updateQuery) === TRUE) {
        echo "Item quantity updated successfully!";
    } else {
        echo "Error updating item quantity: " . $conn->error;
    }
} else {
    $sqlForCart = "INSERT INTO cart (Customer_ID, Item_ID, ItemName, ItemBrand, ItemPrice, ItemPhoto, ItemQty) 
                   VALUES ('$customerID', '$itemID', '$itemName', '$itemBrand', '$itemPrice', '$itemPhoto', '$itemQty')";

    if ($conn->query($sqlForCart) === TRUE) {
        echo "Record inserted successfully!";
    } else {
        echo "Error inserting record: " . $conn->error;
    }
}

$conn->close();
?>
