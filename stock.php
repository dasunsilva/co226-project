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

        $getVegi = "SELECT Item_ID, ItemPrice, ItemName, ItemBrand, ItemQuentityAvailable, ItemPhoto FROM grocery WHERE ItemDescription = 'Vegitable'";
        $resultVegetables = mysqli_query($conn, $getVegi); 

        $getFruit = "SELECT Item_ID, ItemPrice, ItemName, ItemBrand, ItemQuentityAvailable, ItemPhoto FROM grocery WHERE ItemDescription = 'Fruit'";
        $resultFruit = mysqli_query($conn, $getFruit);
        
        $getMeat = "SELECT Item_ID, ItemPrice, ItemName, ItemBrand, ItemQuentityAvailable, ItemPhoto FROM grocery WHERE ItemDescription = 'Meat'";
        $resultMeat = mysqli_query($conn, $getMeat);

        $getFish = "SELECT Item_ID, ItemPrice, ItemName, ItemBrand, ItemQuentityAvailable, ItemPhoto FROM grocery WHERE ItemDescription = 'Fish'";
        $resultFish = mysqli_query($conn, $getFish);

        $getDairy = "SELECT Item_ID, ItemPrice, ItemName, ItemBrand, ItemQuentityAvailable, ItemPhoto FROM grocery WHERE ItemDescription = 'Dairy'";
        $resultDairy = mysqli_query($conn, $getDairy);

        $getHouseHold = "SELECT Item_ID, ItemPrice, ItemName, ItemBrand, ItemQuentityAvailable, ItemPhoto FROM grocery WHERE ItemDescription = 'HouseHold'";
        $resultHouseHold = mysqli_query($conn, $getHouseHold);

        $getBeverage = "SELECT Item_ID, ItemPrice, ItemName, ItemBrand, ItemQuentityAvailable, ItemPhoto FROM grocery WHERE ItemDescription = 'Beverage'";
        $resultBeverages = mysqli_query($conn, $getBeverage);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {        
            foreach ($_POST['newQty'] as $itemId => $newQty) {
                $itemId = $conn->real_escape_string($itemId);
                $newQty = $conn->real_escape_string($newQty);
        
                $sql = "UPDATE grocery SET ItemQuentityAvailable = $newQty WHERE Item_ID = $itemId";
        
                if ($conn->query($sql) === TRUE) {
                    header("Location: stock.php");
                    
                } else {
                    echo "Error updating quantity for ItemID: $itemId - " . $conn->error . "<br>";
                }
            }
        
        }

    ?>
	
	<?php
    session_start();
    $UserName = isset($_SESSION['UserName']) ? $_SESSION['UserName'] : '';
    $FirstName = isset($_SESSION['FirstName']) ? $_SESSION['FirstName'] : '';
    $LastName = isset($_SESSION['LastName']) ? $_SESSION['LastName'] : '';
    $userRole = isset($_SESSION['userRole']) ? $_SESSION['userRole'] : '';

    if($userRole == "Customer"){
        $getCustomerID = "SELECT Customer_ID FROM customer WHERE Username = '$UserName'";
        $resultCustomerID = mysqli_query($conn, $getCustomerID);
        $customerID = $resultCustomerID->fetch_assoc()['Customer_ID'];
        $getCartInfo = "SELECT ItemPrice, ItemName, ItemBrand, ItemPhoto, ItemQty FROM cart WHERE Customer_ID = '$customerID'";
        $resultCartInfo = mysqli_query($conn, $getCartInfo);
    }
    $getEmpType = "SELECT EmployeeType FROM employee WHERE Username = '$UserName'";
    $resultEmpType = mysqli_query($conn, $getEmpType);
    mysqli_close($conn);
    ?>
  

    <div class="page-header">
        <nav class="navbar fixed-top navbar-expand-md navbar-dark bg-transparent" id="page-navigation">
            <div class="container">
                <a href="index.php" class="navbar-brand">
                    <img src="assets/img/logo_transparent.png" alt="">
                </a>

                
                <?php
                if(empty($UserName)){
                    echo '
                        <div class="collapse navbar-collapse" id="navbarcollapse">
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item">
                                    <a href="register.php" class="nav-link">Register</a>
                                </li>
                                <li class="nav-item">
                                    <a href="login.php" class="nav-link">Login</a>
                                </li>
                            </ul>
                        </div>';

                } else if($userRole == "Customer"){
                    header("Location:index.php");
                    echo '
                    <div class="collapse navbar-collapse" id="navbarcollapse">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item dropdown">
                                <a class="nav-link location-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="location-img">
                                        <img src="assets/img/location.png">
                                        <p>Pick Store Location</p>
                                    </div> 
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">';
                                    foreach ($storeNames as $storeName) {
                                        echo '<a class="dropdown-item" href="#">' . $storeName . '</a>';
                                    }
                    echo       '</div>
                            </li>
                            <li class="nav-item">
                                <a href="shop.php" class="nav-link">Shop</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggles" href="javascript:void(0)" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style = "display: flex; align-items:baseline;"  >
                                    <div style = "display:flex"><div class="avatar-header" style="margin-right: 10px;"><img src="assets/img/user.png"></div>';
                                    echo '<p> '. $FirstName . ' ' . $LastName . '</p></div>';
                                    echo '</a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="transaction.php">Transactions History</a>
                                        <a class="dropdown-item" href="logout.php">Logout</a>
                                    </div>
                                </li>
                                <li class="nav-item dropdown">
                                    <a href="javascript:void(0)" class="nav-link dropdown-toggles" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-shopping-basket"></i> <span class="badge badge-primary"></span>
                                    </a>
                                    <div class="dropdown-menu shopping-cart">
                                        <ul>
                                            <li>
                                                <div class="drop-title">Your Cart</div>
                                            </li>
                                            <li>
                                                <div class="shopping-cart-list">';
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
                                    echo "";
                                }
                                
                                echo '</div>
                                    </li>
                                    <li>
                                        <div class="drop-title d-flex justify-content-between">
                                            <span>Total:</span>';
                                echo '<span class="text-primary"><strong>Rs. ' . $totalPrice . '</strong></span>';
                                echo '</div>
                                    </li>
                                    <li class="d-flex justify-content-between pl-3 pr-3 pt-3">
                                        <a href="cart.php" class="btn btn-secondary">View Cart</a>
                                        <a href="checkout.php" class="btn btn-primary">Checkout</a>
                                    </li>
                                </ul>
                                </div>
                                </li>
                                </ul>
                                </div>';
                                
                }else{
                    $empType = $resultEmpType->fetch_assoc()['EmployeeType'];
                    
                    echo '
                    <div class="collapse navbar-collapse" id="navbarcollapse">
                        <ul class="navbar-nav ml-auto">';
                        if($empType == "HR_Management"){
                            echo '
                            <li class="nav-item">
                                <a class="nav-link" href="empAssign.php">Assign Employee</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="empMark.php">Mark Employee</a>
                            
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="empSalary.php">Employee Salary</a>
                            
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="salaryAssign.php">Employee Type</a>

                            </li>';
                        }else if($empType == "StockingTeam"){
                            echo '
                            <li class="nav-item">
                                <a class="nav-link" href="stock.php">Stock</a>
                            </li>
                            ';
                        }else if($empType == "StockManagement"){
                            echo '
                            <li class="nav-item">
                                <a class="nav-link" href="addItems.php">Add Items to Store</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="stock.php">Stock</a>
                            </li>
                                ';
                        }
                        echo '
                            
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggles" href="javascript:void(0)" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style = "display: flex; align-items:baseline;"  >
                                    <div style = "display:flex"><div class="avatar-header" style="margin-right: 10px;"><img src="assets/img/user.png"></div>';
                                    echo '<p> '. $FirstName . ' ' . $LastName . '</p></div>';
                                    echo '</a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">';
                                    echo '
                                        <a class="dropdown-item" href="settings.php">Account Settings</a>
                                        <a class="dropdown-item" href="logout.php">Logout</a>
                                    </div>
                                </li>
                                </ul>
                                </div>';
                }
                      
                ?>
                      
            </div>
        </nav>
    </div>
    <?php
        if(empty($UserName)){
            header("Location: index.php");
        }
    ?>
    <div id="page-content" class="page-content">
        <div class="banner">
            <div class="jumbotron jumbotron-bg text-center rounded-0 cover-img">
                <div class="container">
                    <h1 class="pt-5">
                        Stock Management
                    </h1>
                    <p class="lead">
                        Check and Restock
                    </p>
                </div>
            </div>
        </div>

        <section id="cart">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <form action="stock.php" method="POST">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th width="15%"></th>
                                            <th width="10%" style = "text-align:center;">Item ID</th>
                                            <th width="25%">Products</th>
                                            <th style="padding-left: 12px;">Price</th>
                                            <th width="15%">Quantity Available</th>
                                            <th width="15%">New Quantity</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($resultVegetables->num_rows > 0) {
                                            while ($row = $resultVegetables->fetch_assoc()) {
                                                $itemId = $row['Item_ID'];
                                                $itemPrice = $row['ItemPrice'];
                                                $itemName = $row['ItemName'];
                                                $itemBrand = $row['ItemBrand'];
                                                $itemQtyAvailable = $row['ItemQuentityAvailable'];
                                                $itemPhoto = base64_encode($row["ItemPhoto"]);

                                                echo '<tr>';
                                                echo '<td>';
                                                echo '<img class="d-flex mr-3" src="data:image/jpeg;base64,' . $itemPhoto . '" width="80%" style = "height:80px;">';
                                                echo '</td>';
                                                echo '</td>';
                                                echo '<td style="vertical-align: middle; font-size: 16px; text-align:center;">';
                                                echo $itemId;
                                                echo '</td>';
                                                echo '<td style="vertical-align: middle; font-size: 16px;">';
                                                echo $itemBrand . ' ' . $itemName . ' <br>';
                                                echo '<small>1000g</small>';
                                                echo '<td style="vertical-align: middle; font-size: 16px;">';
                                                echo 'Rs. ' . $itemPrice . '';
                                                echo '</td>';
                                                echo '<td style="vertical-align: middle; font-size: 16px; text-align: center;">';
                                                echo $itemQtyAvailable;
                                                echo '</td>';
                                                echo '<td style="vertical-align: middle;">';
                                                echo '<div class="form-group" style="margin-bottom: 0;">';
                                                echo '<input class="form-control" name="newQty[' . $itemId . ']" placeholder="" type="number" value = "'.$itemQtyAvailable.'">';
                                                echo '</div>';
                                                echo '<td>';
                                                echo '</tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <div class="col text-right total-div">
                                    <button type="submit" class="btn btn-lg btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    

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
