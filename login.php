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
    
    $FirstName = "";
    $LastName = "";
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $host = "localhost";
        $username = "root";
        $password = "";
        $database = "shopmart";

        $conn = mysqli_connect($host, $username, $password, $database);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $username = $_POST['username'];
        $password = $_POST['password'];
        $userRole = $_POST['user_type'];

        if ($userRole == "customer") {
            $getCustomerQuery = "SELECT Password,FirstName,LastName  FROM customer WHERE Username = '$username'";
            $result = mysqli_query($conn, $getCustomerQuery);
            session_start(); // Start the session
            $_SESSION['userRole'] = 'Customer';
        }else{
            $getCustomerQuery = "SELECT Password,FirstName,LastName  FROM employee WHERE Username = '$username'";
            $result = mysqli_query($conn, $getCustomerQuery);
            session_start(); // Start the session
            $_SESSION['userRole'] = 'Employee';
        }

        if ($row = mysqli_fetch_assoc($result)) {
            $hashedPassword = $row['Password'];
            if (password_verify($password, $hashedPassword)) {
                session_start(); // Start the session
                $_SESSION['UserName'] = $username;   
                $_SESSION['FirstName'] = $row['FirstName'];
                $_SESSION['LastName'] = $row['LastName'];
                header("Location: index.php");
            } else {
                echo '<script>alert("Incorrect password. Please try again.");</script>';
            }
        } else {
            echo '<script>alert("Username not found. Please try again.");</script>';
        }

        mysqli_close($conn);
    }
    ?>
    <?php
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
                                <a class="nav-link dropdown-toggles" href="javascript:void(0)" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style = "display: flex; align-items:baseline;"  >
                                    <div style = "display:flex"><div class="avatar-header" style="margin-right: 10px;"><img src="assets/img/user.png"></div>';
                                    echo '<p> '. $FirstName . ' ' . $LastName . '</p></div>';
                    echo       '</a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="transaction.php">Transactions History</a>
                                <a class="dropdown-item" href="settings.php">Settings</a>
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
                                        <li class="d-flex justify-content-between pl-3 pr-3 pt-3" style="display: flex; flex-direction: row-reverse;">

                                            
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
            <div class="jumbotron jumbotron-bg text-center rounded-0 login-page" style="background-image: url('assets/img/bg-header.jpg');">
                <div class="container">
                    <h1 class="pt-5">
                        Login Page
                    </h1>
                    <div class="card card-login mb-5">
                        <div class="card-body">
                            <form class="form-horizontal" method="POST">
                                <div class="form-group row mt-3">
                                    <div class="col-md-12">
                                        <input class="form-control" type="text" name="username" required="" placeholder="Username">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <input class="form-control" type="password" name="password" required="" placeholder="Password">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <div class="select-menu active">
                                            <select name="user_type" class="options">
                                                <option value="customer" class = "option-text">Customer</option>
                                                <option value="employee" class = "option-text">Employee</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row text-center mt-4">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-block text-uppercase">Log In</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
    <script type="text/javascript" src="assets/js/script.js"></script>
</body>
</html>
