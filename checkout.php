<!DOCTYPE html>
<html>
<head>
    <title>Freshcery | Groceries Organic Store</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="assets/fonts/sb-bistro/sb-bistro.css" rel="stylesheet" type="text/css">
    <link href="assets/fonts/font-awesome/font-awesome.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" media="all" href="assets/packages/bootstrap/bootstrap.css">
    <link rel="stylesheet" type="text/css" media="all" href="assets/packages/o2system-ui/o2system-ui.css">
    <link rel="stylesheet" type="text/css" media="all" href="assets/packages/owl-carousel/owl-carousel.css">
    <link rel="stylesheet" type="text/css" media="all" href="assets/packages/cloudzoom/cloudzoom.css">
    <link rel="stylesheet" type="text/css" media="all" href="assets/packages/thumbelina/thumbelina.css">
    <link rel="stylesheet" type="text/css" media="all" href="assets/packages/bootstrap-touchspin/bootstrap-touchspin.css">
    <link rel="stylesheet" type="text/css" media="all" href="assets/css/theme.css">

</head>
<body>
    <?php
        $host = "localhost";
        $username = "root";
        $password = "";
        $database = "shopmart";

        $conn = mysqli_connect($host, $username, $password, $database);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $getNames = "SELECT storeName FROM store";
        $result = mysqli_query($conn, $getNames);

        $storeNames = array();

        while ($row = mysqli_fetch_assoc($result)) {
            $storeNames[] = $row['storeName'];
        }


        $customerID = 1;

        $getCartInfo = "SELECT ItemPrice, ItemName, ItemBrand, ItemPhoto, ItemQty FROM cart WHERE Customer_ID = '$customerID'";
        $resultCartInfo = mysqli_query($conn, $getCartInfo);

        $totalPrice = 0;

        $getCartInfo2 = "SELECT ItemPrice, ItemName, ItemBrand, ItemQty FROM cart WHERE Customer_ID = '$customerID'";
        $resultCartInfo2 = mysqli_query($conn, $getCartInfo2);

        $getCartInfo3 = "SELECT Item_ID, ItemQty, ItemName, ItemBrand, ItemPrice FROM cart WHERE Customer_ID = '$customerID'";
        $resultCartInfo3 = mysqli_query($conn, $getCartInfo3);
        
        $totalPrice = 0;
        $subTotal = 0;

        if (isset($_POST['checkout'])) {

            $firstName = $_POST['first_name'];
            $lastName = $_POST['last_name'];
            $address = $_POST['address'];
            $city = $_POST['city'];
            $state = $_POST['state'];
            $postcode = $_POST['postcode'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $note = $_POST['note'];

            $billingDetails = array(
                "firstName" => $firstName,
                "lastName" => $lastName,
                "address" => $address,
                "city" => $city,
                "state" => $state,
                "postcode" => $postcode,
                "email" => $email,
                "phone" => $phone,
                "note" => $note,
            );
            
            $orders = array(); 
            $subTotalTemp = 0;
            
            if ($resultCartInfo3->num_rows > 0) {
                while ($row = $resultCartInfo3->fetch_assoc()) {
                    $itemID = $row['Item_ID'];
                    $itemQty = $row['ItemQty'];
                    $itemName = $row['ItemName'];
                    $itemBrand = $row['ItemBrand'];
                    $itemPrice = $row['ItemPrice'];
            
                    $itemTotal = $itemPrice * $itemQty;
                    $subTotalTemp = $subTotalTemp + $itemTotal;
            
                    $orderDetails = array(
                        "ItemID" => $itemID,
                        "ItemQty" => $itemQty,
                        "ItemName" => $itemName,
                        "ItemBrand" => $itemBrand,
                        "ItemPrice" => $itemPrice,
                        "ItemTotal" => $itemTotal,
                    );
            
                    $orders[] = $orderDetails;
            
                    $getAvailableQty = "SELECT ItemQuentityAvailable FROM grocery WHERE Item_ID = $itemID";
                    $resultAvailableQty = mysqli_query($conn, $getAvailableQty);
                    $availableQty = $resultAvailableQty->fetch_assoc()['ItemQuentityAvailable'];
                    $availableQty = $availableQty - $itemQty;
            
                    $updateQuery = "UPDATE grocery SET ItemQuentityAvailable = '$availableQty' WHERE Item_ID = '$itemID'";
                    mysqli_query($conn, $updateQuery);
            
                    $deleteQuery = "DELETE FROM cart WHERE (Item_ID = '$itemID' && Customer_ID = '$customerID')";
                    mysqli_query($conn, $deleteQuery);
                }
            }
            
            $orderDetails = array(
                "billing" => $billingDetails,
                "orders" => $orders,
                "subTotal" => $subTotalTemp,
                "index" => count($orders),
            );
            $orderDetailsJSON = json_encode($orderDetails);
            $today = date("Y-m-d");
            $insertQuery = "INSERT INTO customer_order (Customer_ID,OrderDetails,SubTotal,OrderDate) VALUES ('$customerID','$orderDetailsJSON', '$subTotalTemp','$today')";
            if(mysqli_query($conn, $insertQuery)){
                header("Location: checkout.php");
            }
        }


        mysqli_close($conn);
    ?>
	
	<?php
    session_start(); // Start the session

    // Check if the session variables are set before using them
    $FirstName = isset($_SESSION['FirstName']) ? $_SESSION['FirstName'] : '';
    $LastName = isset($_SESSION['LastName']) ? $_SESSION['LastName'] : '';
    ?>
	
    <div class="page-header">
        <nav class="navbar fixed-top navbar-expand-md navbar-dark bg-transparent" id="page-navigation">
            <div class="container">
                <a href="index.php" class="navbar-brand">
                    <img src="assets/img/logo_transparent.png" alt="">
                </a>

                <div class="collapse navbar-collapse" id="navbarcollapse">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link location-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="location-img">
                                    <img src="assets/img/location.png">
                                    <p>Pick Store Location</p>
                                </div> 
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <?php
                                foreach ($storeNames as $storeName) {
                                    echo '<a class="dropdown-item" href="#">' . $storeName . '</a>';
                                }
                                ?>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="shop.php" class="nav-link">Shop</a>
                        </li>
                        <li class="nav-item">
                            <a href="register.php" class="nav-link">Register</a>
                        </li>
                        <li class="nav-item">
                            <a href="login.php" class="nav-link">Login</a>
                        </li>
                        <li class="nav-item dropdown">
                            <li class="nav-item dropdown">
                               <a class="nav-link" href="setting.php">
                                   <div class="avatar-header"><img src="assets/img/user.png"></div>
                                   <?php
                                       echo '<p> '. $FirstName . ' ' . $LastName . '</p>';
                                   ?>
                               </a>
                          </li>
                          </li>
                        <li class="nav-item dropdown">
                            <a href="javascript:void(0)" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-shopping-basket"></i> <span class="badge badge-primary"></span>
                            </a>
                            <div class="dropdown-menu shopping-cart">
                                <ul>
                                    <li>
                                        <div class="drop-title">Your Cart</div>
                                    </li>
                                    <li>
                                        <div class="shopping-cart-list">
                                            <?php
                                                if ($resultCartInfo->num_rows > 0) {
                                                    while ($row = $resultCartInfo->fetch_assoc()) {
                                                        $itemPrice = $row['ItemPrice'];
                                                        $itemName = $row['ItemName'];
                                                        $itemBrand = $row['ItemBrand'];
                                                        $itemPhoto = $row['ItemPhoto'];
                                                        $itemQty = $row['ItemQty'];
                                                
                                                        $totalPrice = $totalPrice + ($itemPrice * $itemQty);

                                                        echo '<div class="media">';
                                                        echo '<img class="d-flex mr-3" src="data:image/jpeg;base64,' . $itemPhoto . '" width="60">';
                                                        echo '<div class="media-body">';
                                                        echo '<h5><a href="javascript:void(0)">' . $itemBrand . ' ' . $itemName . '</a></h5>';
                                                        echo '<p class="price">';
                                                        echo '<span>Rs. ' . $itemPrice . '</span>';
                                                        echo '</p>';
                                                        echo '<p class="text-muted">Qty: ' . $itemQty . '</p>';
                                                        echo '</div>';
                                                        echo '</div>';
                                                    }
                                                } else {
                                                    echo "No items found for the given Customer_ID: " . $customerID;
                                                }
                                            ?>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="drop-title d-flex justify-content-between">
                                            <span>Total:</span>
                                            <?php
                                                echo '<span class="text-primary"><strong>Rs. ' . $totalPrice . '</strong></span>';
                                            ?>
                                        </div>
                                    </li>
                                    <li class="d-flex justify-content-between pl-3 pr-3 pt-3">
                                        <a href="cart.php" class="btn btn-secondary">View Cart</a>
                                        <a href="checkout.php" class="btn btn-primary">Checkout</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>
        </nav>
    </div>
    <div id="page-content" class="page-content">
        <div class="banner">
            <div class="jumbotron jumbotron-bg text-center rounded-0 cover-img">
                <div class="container">
                    <h1 class="pt-5">
                        Checkout
                    </h1>
                    <p class="lead">
                        Save time and leave the groceries to us.
                    </p>
                </div>
            </div>
        </div>

        <section id="checkout">
            <div class="container">
                <div class="row">
                    <form id="checkoutForm" action="checkout.php" class="bill-detail" method="POST" style = "display:flex; flex-grow: 0.9; justify-content:space-between;">
                        <div class="col-xs-12 col-sm-7">
                        <h5 class="mb-3">BILLING DETAILS</h5>
                            <fieldset style = "width:95%;">
                                <div class="form-group row">
                                    <div class="col">
                                        <input class="form-control" name = "first_name" placeholder="First Name" type="text" required>
                                    </div>
                                    <div class="col">
                                        <input class="form-control" name = "last_name" placeholder="Last Name" type="text" required>
                                    </div>
                                </div>
                            
                                <div class="form-group">
                                    <textarea class="form-control" name = "address" placeholder="Address" required></textarea>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" name = "city" placeholder="Town / City" type="text" required>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" name = "state" placeholder="State / Country" type="text" required>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" name = "postcode" placeholder="Postcode / Zip" type="text" required>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <input class="form-control"name= "email" placeholder="Email Address" type="email">
                                    </div>
                                    <div class="col">
                                        <input class="form-control" name = "phone" placeholder="Phone Number" type="tel" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <textarea class="form-control" name = "note" placeholder="Order Notes"></textarea>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-xs-12 col-sm-5">
                        <div class="holder">
                            <h5 class="mb-3">YOUR ORDER</h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Products</th>
                                            <th class="text-right">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        if ($resultCartInfo2->num_rows > 0) {
                                            while ($row = $resultCartInfo2->fetch_assoc()) {
                                                $itemPrice = $row['ItemPrice'];
                                                $itemName = $row['ItemName'];
                                                $itemBrand = $row['ItemBrand'];
                                                $itemQty = $row['ItemQty'];
                                                
                                                echo '<tr>';
                                                echo '<td>';
                                                echo $itemBrand . ' ' . $itemName . ' x ' . $itemQty. ' <br>';
                                                echo '</td>';
                                                echo '<td style = "text-align:end;">';
                                                $total = $itemPrice * $itemQty;
                                                echo  'Rs. ' . $total .'.00';
                                                echo '</td>';
                                                $subTotal = $subTotal + $total;
                                            }
                                        }
                                    ?>
                                    </tbody>
                                    <tfooter>
                                        <tr>
                                            <td>
                                                <strong>Cart Subtotal</strong>
                                            </td>
                                            <td class="text-right">
                                                <?php
                                                echo 'Rs. ' .$subTotal. ' .00';
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>Shipping</strong>
                                            </td>
                                            <td class="text-right">
                                                Rs 200.00
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <strong>ORDER TOTAL</strong>
                                            </td>
                                            <td class="text-right">
                                                <strong>
                                                    <?php
                                                        $finalPrice = $subTotal + 200;
                                                        echo 'Rs. ' . $finalPrice. '.00';
                                                    ?>
                                                </strong>
                                            </td>
                                        </tr>
                                    </tfooter>
                                </table>
                            </div>
                        </div>
                        <p class="text-right mt-3">
                            <input checked="" type="checkbox"> I’ve read &amp; accept the <a href="#">terms &amp; conditions</a>
                        </p>
                        <button type="submit" name = "checkout" class="btn btn-lg btn-primary" style="float:right;">PROCEED TO CHECKOUT</button>
                        <div class="clearfix">
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
    <script type="text/javascript" src="assets/js/jquery.js"></script>
    <script type="text/javascript" src="assets/js/jquery-migrate.js"></script>
    <script type="text/javascript" src="assets/packages/bootstrap/libraries/popper.js"></script>
    <script type="text/javascript" src="assets/packages/bootstrap/bootstrap.js"></script>
    <script type="text/javascript" src="assets/packages/o2system-ui/o2system-ui.js"></script>
    <script type="text/javascript" src="assets/packages/owl-carousel/owl-carousel.js"></script>
    <script type="text/javascript" src="assets/packages/cloudzoom/cloudzoom.js"></script>
    <script type="text/javascript" src="assets/packages/thumbelina/thumbelina.js"></script>
    <script type="text/javascript" src="assets/packages/bootstrap-touchspin/bootstrap-touchspin.js"></script>
    <script type="text/javascript" src="assets/js/theme.js"></script>
</body>
</html>
