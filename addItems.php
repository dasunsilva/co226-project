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

        if (isset($_POST['insert'])) {
            $ItemPrice = $_POST['ItemPrice'];
            $ItemDesc = $_POST['ItemDescription'];
            $ItemName = $_POST['ItemName'];
            $ItemBrand = $_POST['ItemBrand'];
            $ItemQty = $_POST['ItemQuentityAvailable'];

            if (isset($_FILES['ItemImage']) && $_FILES['ItemImage']['error'] === UPLOAD_ERR_OK) {
                $imagePath = $_FILES['ItemImage']['tmp_name'];
                $imageData = addslashes(file_get_contents($imagePath));
        
                $insertQuery = "INSERT INTO grocery (ItemPrice, ItemDescription, ItemName, ItemBrand, ItemQuentityAvailable, ItemPhoto)
                                VALUES ('$ItemPrice', '$ItemDesc', '$ItemName', '$ItemBrand', '$ItemQty', '$imageData')";
        
                if (mysqli_query($conn, $insertQuery)) {
                    header("Location: addItems.php");
                    exit;
                } else {
                    echo '<script>alert("Error updating record: ' . mysqli_error($conn) . '");</script>';
                }
            } else {
                echo "Image upload failed.";
            }
            
        }
        session_start();
        $UserName = isset($_SESSION['UserName']) ? $_SESSION['UserName'] : '';

    ?>
    <?php
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
        $totalPrice = 0;
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
                    echo'
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
                    header("Location: index.php");
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
                                    <li class="d-flex justify-content-between pl-3 pr-3 pt-3" style="display: flex; flex-direction: row-reverse;">

                                        
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
                        Add Items to the store
                    </p>
                </div>
            </div>
        </div>

        <section id="checkout">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xs-12 col-sm-6">
                        <h5 class="mb-3">Add item</h5>
                        <form action="addItems.php" class="bill-detail" method="POST" enctype="multipart/form-data">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" name="ItemName" placeholder="Grocery Item Name" type="text">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" name="ItemBrand" placeholder="Grocery Item Brand" type="text">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" name="ItemDescription" placeholder="Grocery Type" type="text">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" name="ItemPrice" placeholder="Grocery Item Price" type="number">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" name="ItemQuentityAvailable" placeholder="Grocery Quantity" type="number">
                                </div>
                                <div class="form-group" style="display: flex; align-items: center;">
                                    <label for="ItemImage" style="margin-left: 10px;flex-grow: 1;font-weight: bold;margin-bottom: 0;">Grocery Item Image</label>
                                    <input class="form-control" name="ItemImage" type="file" style="width: 60%; border:none;">
                                </div>
                                <div class="form-group text-right">
                                    <button type="submit" name="insert" class="btn btn-primary">Insert</button>
                                    <div class="clearfix"></div>
                                </div>
                                
                            </fieldset>
                        </form>
                    </div>
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
