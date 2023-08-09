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

        $getStrNames = "SELECT storeName FROM store";
        $resultStrNames = mysqli_query($conn, $getStrNames);  
        $storeNames = array();

        while ($row = mysqli_fetch_assoc($resultStrNames)) {
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

        $customerID = 1;

        $getCartInfo = "SELECT ItemPrice, ItemName, ItemBrand, ItemPhoto, ItemQty FROM cart WHERE Customer_ID = '$customerID'";
        $resultCartInfo = mysqli_query($conn, $getCartInfo);

        $totalPrice = 0;

        mysqli_close($conn);
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
                            <a class="nav-link location-toggle" href="javascript:void(0)" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                                <div class="avatar-header"><img src="assets/img/user.png"></div> John Doe
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
            <div class="jumbotron jumbotron-bg text-center rounded-0 cover-img");">
                <div class="container">
                    <h1 class="pt-5">
                        Shop with us
                    </h1>
                    <p class="lead">
                        Enjoy the fresheness of ShopMart
                    </p>
                </div>
            </div>
        </div>

        <section id="vegetables" class="gray-bg">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="title">Vegetables</h2>
                        <div class="product-carousel owl-carousel">
                            <?php
                            if ($resultVegetables->num_rows > 0) {
                                while ($row = $resultVegetables->fetch_assoc()) {
                                    $itemID = $row["Item_ID"];
                                    $itemPrice = $row["ItemPrice"];
                                    $itemName = $row["ItemName"];
                                    $itemBrand = $row["ItemBrand"];
                                    $itemQuantityAvailable = $row["ItemQuentityAvailable"];
                                    $itemPhoto = base64_encode($row["ItemPhoto"]); // Convert BLOB to base64 for displaying the image in HTML

                                    $htmlTemplate = '<div class="item">
                                                        <div class="card card-product">
                                                            <div class="card-badge">
                                                                <img src="data:image/jpeg;base64,' . $itemPhoto . '" alt="Card image 2" class="card-img-top" style = "height: 300px">
                                                            </div>
                                                            <div class="card-body">
                                                                <h4 class="card-title">
                                                                    <p>' . $itemBrand . ' ' . $itemName . '</p>
                                                                </h4>
                                                                <div class="card-price">
                                                                    <span class="reguler">Rs.' . $itemPrice . '/kg</span>
                                                                </div>
                                                                <a href="#" class="btn btn-block btn-primary" id="' . $itemID . '" onclick="updateCart(\'Vegetable\', \'' . $itemID . '\', \'' . $itemName . '\', \'' . $itemBrand . '\', \'' . $itemPrice . '\', \'' . $itemPhoto . '\')">
                                                                    Add to Cart
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>';

                                    echo $htmlTemplate;
                                }
                            } else {
                                echo "No vegetables found in the GROCERY table.";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section id="fruits" class="gray-bg">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="title">Fruits</h2>
                        <div class="product-carousel owl-carousel">
                            <?php
                            if ($resultFruit->num_rows > 0) {
                                while ($row = $resultFruit->fetch_assoc()) {
                                    $itemPrice = $row["ItemPrice"];
                                    $itemName = $row["ItemName"];
                                    $itemBrand = $row["ItemBrand"];
                                    $itemQuantityAvailable = $row["ItemQuentityAvailable"];
                                    $itemPhoto = base64_encode($row["ItemPhoto"]); // Convert BLOB to base64 for displaying the image in HTML

                                    $htmlTemplate = '<div class="item">
                                                        <div class="card card-product">
                                                            <div class="card-badge">
                                                                <img src="data:image/jpeg;base64,' . $itemPhoto . '" alt="Card image 2" class="card-img-top" style = "height: 300px">
                                                            </div>
                                                            <div class="card-body">
                                                                <h4 class="card-title">
                                                                    <p>' . $itemBrand . ' ' . $itemName . '</p>
                                                                </h4>
                                                                <div class="card-price">
                                                                    <span class="reguler">Rs.' . $itemPrice . '/kg</span>
                                                                </div>
                                                                <a href="#" class="btn btn-block btn-primary" id="' . $itemID . '" onclick="updateCart(\'Vegetable\', \'' . $itemID . '\', \'' . $itemName . '\', \'' . $itemBrand . '\', \'' . $itemPrice . '\', \'' . $itemPhoto . '\')">
                                                                    Add to Cart
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>';

                                    // Echo the updated HTML template
                                    echo $htmlTemplate;
                                }
                            } else {
                                echo "No Fruits found in the GROCERY table.";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="meat" class="gray-bg">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="title">Meat</h2>
                        <div class="product-carousel owl-carousel">
                            <?php
                            if ($resultMeat->num_rows > 0) {
                                while ($row = $resultMeat->fetch_assoc()) {
                                    $itemPrice = $row["ItemPrice"];
                                    $itemName = $row["ItemName"];
                                    $itemBrand = $row["ItemBrand"];
                                    $itemQuantityAvailable = $row["ItemQuentityAvailable"];
                                    $itemPhoto = base64_encode($row["ItemPhoto"]); // Convert BLOB to base64 for displaying the image in HTML

                                    $htmlTemplate = '<div class="item">
                                                        <div class="card card-product">
                                                            <div class="card-badge">
                                                                <img src="data:image/jpeg;base64,' . $itemPhoto . '" alt="Card image 2" class="card-img-top" style = "height: 300px">
                                                            </div>
                                                            <div class="card-body">
                                                                <h4 class="card-title">
                                                                    <p>' . $itemBrand . ' ' . $itemName . '</p>
                                                                </h4>
                                                                <div class="card-price">
                                                                    <span class="reguler">Rs.' . $itemPrice . '/kg</span>
                                                                </div>
                                                                <a href="#" class="btn btn-block btn-primary" id="' . $itemID . '" onclick="updateCart(\'Vegetable\', \'' . $itemID . '\', \'' . $itemName . '\', \'' . $itemBrand . '\', \'' . $itemPrice . '\', \'' . $itemPhoto . '\')">
                                                                    Add to Cart
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>';

                                    // Echo the updated HTML template
                                    echo $htmlTemplate;
                                }
                            } else {
                                echo "No Meat found in the GROCERY table.";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="fish" class="gray-bg">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="title">Fish</h2>
                        <div class="product-carousel owl-carousel">
                            <?php
                            if ($resultFish->num_rows > 0) {
                                while ($row = $resultFish->fetch_assoc()) {
                                    $itemPrice = $row["ItemPrice"];
                                    $itemName = $row["ItemName"];
                                    $itemBrand = $row["ItemBrand"];
                                    $itemQuantityAvailable = $row["ItemQuentityAvailable"];
                                    $itemPhoto = base64_encode($row["ItemPhoto"]); // Convert BLOB to base64 for displaying the image in HTML

                                    $htmlTemplate = '<div class="item">
                                                        <div class="card card-product">
                                                            <div class="card-badge">
                                                                <img src="data:image/jpeg;base64,' . $itemPhoto . '" alt="Card image 2" class="card-img-top" style = "height: 300px">
                                                            </div>
                                                            <div class="card-body">
                                                                <h4 class="card-title">
                                                                    <p>' . $itemBrand . ' ' . $itemName . '</p>
                                                                </h4>
                                                                <div class="card-price">
                                                                    <span class="reguler">Rs.' . $itemPrice . '/kg</span>
                                                                </div>
                                                                <a href="#" class="btn btn-block btn-primary" id="' . $itemID . '" onclick="updateCart(\'Vegetable\', \'' . $itemID . '\', \'' . $itemName . '\', \'' . $itemBrand . '\', \'' . $itemPrice . '\', \'' . $itemPhoto . '\')">
                                                                    Add to Cart
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>';

                                    // Echo the updated HTML template
                                    echo $htmlTemplate;
                                }
                            } else {
                                echo "No Fish found in the GROCERY table.";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="HouseHold" class="gray-bg">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="title">HouseHold</h2>
                        <div class="product-carousel owl-carousel">
                            <?php
                            if ($resultHouseHold->num_rows > 0) {
                                while ($row = $resultHouseHold->fetch_assoc()) {
                                    $itemPrice = $row["ItemPrice"];
                                    $itemName = $row["ItemName"];
                                    $itemBrand = $row["ItemBrand"];
                                    $itemQuantityAvailable = $row["ItemQuentityAvailable"];
                                    $itemPhoto = base64_encode($row["ItemPhoto"]); // Convert BLOB to base64 for displaying the image in HTML

                                    $htmlTemplate = '<div class="item">
                                                        <div class="card card-product">
                                                            <div class="card-badge">
                                                                <img src="data:image/jpeg;base64,' . $itemPhoto . '" alt="Card image 2" class="card-img-top" style = "height: 300px">
                                                            </div>
                                                            <div class="card-body">
                                                                <h4 class="card-title">
                                                                    <p>' . $itemBrand . ' ' . $itemName . '</p>
                                                                </h4>
                                                                <div class="card-price">
                                                                    <span class="reguler">Rs.' . $itemPrice . '/kg</span>
                                                                </div>
                                                                <a href="#" class="btn btn-block btn-primary" id="' . $itemID . '" onclick="updateCart(\'Vegetable\', \'' . $itemID . '\', \'' . $itemName . '\', \'' . $itemBrand . '\', \'' . $itemPrice . '\', \'' . $itemPhoto . '\')">
                                                                    Add to Cart
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>';

                                    // Echo the updated HTML template
                                    echo $htmlTemplate;
                                }
                            } else {
                                echo "No HouseHold found in the GROCERY table.";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="Dairy" class="gray-bg">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="title">Dairy</h2>
                        <div class="product-carousel owl-carousel">
                            <?php
                            if ($resultDairy->num_rows > 0) {
                                while ($row = $resultDairy->fetch_assoc()) {
                                    $itemPrice = $row["ItemPrice"];
                                    $itemName = $row["ItemName"];
                                    $itemBrand = $row["ItemBrand"];
                                    $itemQuantityAvailable = $row["ItemQuentityAvailable"];
                                    $itemPhoto = base64_encode($row["ItemPhoto"]); // Convert BLOB to base64 for displaying the image in HTML

                                    $htmlTemplate = '<div class="item">
                                                        <div class="card card-product">
                                                            <div class="card-badge">
                                                                <img src="data:image/jpeg;base64,' . $itemPhoto . '" alt="Card image 2" class="card-img-top" style = "height: 300px">
                                                            </div>
                                                            <div class="card-body">
                                                                <h4 class="card-title">
                                                                    <p>' . $itemBrand . ' ' . $itemName . '</p>
                                                                </h4>
                                                                <div class="card-price">
                                                                    <span class="reguler">Rs.' . $itemPrice . '/kg</span>
                                                                </div>
                                                                <a href="#" class="btn btn-block btn-primary" id="' . $itemID . '" onclick="updateCart(\'Vegetable\', \'' . $itemID . '\', \'' . $itemName . '\', \'' . $itemBrand . '\', \'' . $itemPrice . '\', \'' . $itemPhoto . '\')">
                                                                    Add to Cart
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>';

                                    // Echo the updated HTML template
                                    echo $htmlTemplate;
                                }
                            } else {
                                echo "No Dairy found in the GROCERY table.";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="Beverages" class="gray-bg">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="title">Beverages</h2>
                        <div class="product-carousel owl-carousel">
                            <?php
                            if ($resultBeverages->num_rows > 0) {
                                while ($row = $resultBeverages->fetch_assoc()) {
                                    $itemPrice = $row["ItemPrice"];
                                    $itemName = $row["ItemName"];
                                    $itemBrand = $row["ItemBrand"];
                                    $itemQuantityAvailable = $row["ItemQuentityAvailable"];
                                    $itemPhoto = base64_encode($row["ItemPhoto"]); // Convert BLOB to base64 for displaying the image in HTML

                                    $htmlTemplate = '<div class="item">
                                                        <div class="card card-product">
                                                            <div class="card-badge">
                                                                <img src="data:image/jpeg;base64,' . $itemPhoto . '" alt="Card image 2" class="card-img-top" style = "height: 300px">
                                                            </div>
                                                            <div class="card-body">
                                                                <h4 class="card-title">
                                                                    <p>' . $itemBrand . ' ' . $itemName . '</p>
                                                                </h4>
                                                                <div class="card-price">
                                                                    <span class="reguler">Rs.' . $itemPrice . '/kg</span>
                                                                </div>
                                                                <a href="#" class="btn btn-block btn-primary" id="' . $itemID . '" onclick="updateCart(\'Vegetable\', \'' . $itemID . '\', \'' . $itemName . '\', \'' . $itemBrand . '\', \'' . $itemPrice . '\', \'' . $itemPhoto . '\')">
                                                                    Add to Cart
                                                                </a>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>';

                                    // Echo the updated HTML template
                                    echo $htmlTemplate;
                                }
                            } else {
                                echo "No Beverages found in the GROCERY table.";
                            }
                            ?>
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
    <script type="text/javascript" src="assets/js/functions.js"></script>
</body>
</html>
