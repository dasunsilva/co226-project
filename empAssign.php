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

        $getEmployeeInfo = "SELECT Employee_ID, FirstName, MiddleName, LastName, storeID , EmployeeType, EmployeeWorkingDays FROM employee";
        $resultEmpInfo = mysqli_query($conn, $getEmployeeInfo);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {        
            foreach ($_POST['selectAction'] as $empId => $employeeType) {
                $employeeType = mysqli_real_escape_string($conn, $employeeType);
                $updateEmployeeTypeQuery = "UPDATE employee SET EmployeeType = '$employeeType' WHERE Employee_ID = '$empId'";
                mysqli_query($conn, $updateEmployeeTypeQuery);
            }

            foreach ($_POST['selectActionStore'] as $empId => $storeName) {
                $storeName = mysqli_real_escape_string($conn, $storeName);

                $getStoreID = "SELECT storeID from store WHERE storeName = '$storeName'";
                $storeIDResult = mysqli_query($conn, $getStoreID);
                $tempStoreID = mysqli_fetch_assoc($storeIDResult)['storeID'];

                $updateEmployeeTypeQuery2 = "UPDATE employee SET storeID = '$tempStoreID' WHERE Employee_ID = '$empId'";
                mysqli_query($conn, $updateEmployeeTypeQuery2);

            }

            if ($conn->query($updateEmployeeTypeQuery) === TRUE && $conn->query($updateEmployeeTypeQuery2) === TRUE) {
                header("Location: empAssign.php");
                
            } else {
                echo "Error updating employee";
            }
                
        }

        mysqli_close($conn);
    ?>
    
	<?php
    session_start();

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
                            <form action="empAssign.php" method="POST">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th width="15%" style = "text-align:center;">Employee ID</th>
                                            <th width="35%">Name</th>
                                            <th width = "25%" style = "padding-left:20px;">Store Name</th>
                                            <th width="25%" style = "padding-left:20px;">Employee Type</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($resultEmpInfo->num_rows > 0) {
                                            while ($row = $resultEmpInfo->fetch_assoc()) {
                                                $empId = $row['Employee_ID'];
                                                $firstName = $row['FirstName'];
                                                $middleName = $row['MiddleName'];
                                                $lastName = $row['LastName'];
                                                $storeID = $row['storeID'];

                                                $host = "localhost";
                                                $username = "root";
                                                $password = "";
                                                $database = "shopmart";

                                                $conn = mysqli_connect($host, $username, $password, $database);

                                                if (!$conn) {
                                                    die("Connection failed: " . mysqli_connect_error());
                                                }

                                                $getStoreName = "SELECT storeName from store WHERE storeID = '$storeID'";
                                                $storeNameResult = mysqli_query($conn, $getStoreName);
                                                $tempStoreName = mysqli_fetch_assoc($storeNameResult)['storeName'];

                                                mysqli_close($conn);


                                                $employeeType = $row['EmployeeType'];
                                                $employeeWorkingDays = $row['EmployeeWorkingDays'];

                                                echo '<tr>';
                                                echo '</td>';
                                                echo '<td style="vertical-align: middle; font-size: 16px; text-align:center;">';
                                                echo $empId;
                                                echo '</td>';
                                                echo '<td style="vertical-align: middle; font-size: 16px;">';
                                                echo $firstName . ' ' . $middleName . ' ' . $lastName . ' <br>';
                                                echo '<td style="vertical-align: middle;">';
                                                echo '<div class="form-group" style="margin-bottom: 0;">';
                                                echo '<select class="form-control" name="selectActionStore[' . $empId . ']">';
                                                foreach ($storeNames as $storeName) {
                                                    if($storeName == $tempStoreName){
                                                        echo '<option value="'. $storeName . '" selected>'.$storeName.'</option>';
                                                    }else{
                                                        echo '<option value="'. $storeName . '">'.$storeName.'</option>';
                                                    }
                                                }
                                                echo '</select>';
                                                echo '</div>';
                                                echo '</td>';
                                                echo '<td style="vertical-align: middle;">';
                                                echo '<div class="form-group" style="margin-bottom: 0;">';
                                                echo '<select class="form-control" name="selectAction[' . $empId . ']">';
                                                echo '<option value="Cleaner"' . ($employeeType == "Cleaner" ? ' selected' : '') . '>Cleaner</option>';
                                                echo '<option value="Cashier"' . ($employeeType == "Cashier" ? ' selected' : '') . '>Cashier</option>';
                                                echo '<option value="StockingTeam"' . ($employeeType == "StockingTeam" ? ' selected' : '') . '>Stocking Team</option>';
                                                echo '<option value="StockManagement"' . ($employeeType == "StockManagement" ? ' selected' : '') . '>Stock Management</option>';
                                                echo '<option value="HR_Management"' . ($employeeType == "HR_Management" ? ' selected' : '') . '>HR Management</option>';
                                                echo '</select>';
                                                echo '</div>';
                                                echo '</td>';
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
