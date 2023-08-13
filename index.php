<!DOCTYPE html>
<html>
<head>
    <title>Freshcery | Groceries Organic Store</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet">
    <link href="assets/fonts/sb-bistro/sb-bistro.css" rel="stylesheet">
    <link href="assets/fonts/font-awesome/font-awesome.css" rel="stylesheet">

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

        mysqli_close($conn);
    ?>

    <?php
    session_start();
    $UserName = isset($_SESSION['UserName']) ? $_SESSION['UserName'] : '';
    $FirstName = isset($_SESSION['FirstName']) ? $_SESSION['FirstName'] : '';
    $LastName = isset($_SESSION['LastName']) ? $_SESSION['LastName'] : '';
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
                                    <a href="shop.php" class="nav-link">Shop</a>
                                </li>
                                <li class="nav-item">
                                    <a href="register.php" class="nav-link">Register</a>
                                </li>
                                <li class="nav-item">
                                    <a href="login.php" class="nav-link">Login</a>
                                </li>
                            </ul>
                        </div>';

                } else {
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
                                <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style = "display: flex; align-items:baseline;"  >
                                    <div style = "display:flex"><div class="avatar-header" style="margin-right: 10px;"><img src="assets/img/user.png"></div>';
                                    echo '<p> '. $FirstName . ' ' . $LastName . '</p></div>';
                    echo       '</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="transaction.php">Transactions History</a>
                                <a class="dropdown-item" href="setting.php">Settings</a>
                                <a class="dropdown-item" href="logout.php">Logout</a>
                            </div>
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
                                                <div class="media">
                                                    <img class="d-flex mr-3" src="assets/img/user.png" width="60">
                                                    <div class="media-body">
                                                        <h5><a href="javascript:void(0)">Carrot</a></h5>
                                                        <p class="price">
                                                            <span class="discount text-muted">Rp. 700.000</span>
                                                            <span>Rp. 100.000</span>
                                                        </p>
                                                        <p class="text-muted">Qty: 1</p>
                                                    </div>
                                                </div>
                                                <div class="media">
                                                    <img class="d-flex mr-3" src="assets/img/user.png" width="60">
                                                    <div class="media-body">
                                                        <h5><a href="javascript:void(0)">Carrot</a></h5>
                                                        <p class="price">
                                                            <span class="discount text-muted">Rp. 700.000</span>
                                                            <span>Rp. 100.000</span>
                                                        </p>
                                                        <p class="text-muted">Qty: 1</p>
                                                    </div>
                                                </div>
                                                <div class="media">
                                                    <img class="d-flex mr-3" src="assets/img/user.png" width="60">
                                                    <div class="media-body">
                                                        <h5><a href="javascript:void(0)">Carrot</a></h5>
                                                        <p class="price">
                                                            <span class="discount text-muted">Rp. 700.000</span>
                                                            <span>Rp. 100.000</span>
                                                        </p>
                                                        <p class="text-muted">Qty: 1</p>
                                                    </div>
                                                </div>
                                                <div class="media">
                                                    <img class="d-flex mr-3" src="assets/img/user.png" width="60">
                                                    <div class="media-body">
                                                        <h5><a href="javascript:void(0)">Carrot</a></h5>
                                                        <p class="price">
                                                            <span class="discount text-muted">Rp. 700.000</span>
                                                            <span>Rp. 100.000</span>
                                                        </p>
                                                        <p class="text-muted">Qty: 1</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="drop-title d-flex justify-content-between">
                                                <span>Total:</span>
                                                <span class="text-primary"><strong>Rp. 2000.000</strong></span>
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
                    </div>';
                }
                      
                ?>
                      
            </div>
        </nav>
    </div>
    <div id="page-content" class="page-content">
        <div class="banner">
            <div class="jumbotron jumbotron-video text-center mb-0 rounded-0 cover-img">
                <div class="container">
                    <h1 class="pt-5">
                        Best place for your<br>
                        daily needs
                    </h1>
                    <p class="lead">
                        Always Fresh Everyday.
                    </p>
                </div>
            </div>
        </div>

        <section id="categories" class="pb-0 gray-bg">
            <h2 class="title">Categories</h2>
            <div class="landing-categories owl-carousel">
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
