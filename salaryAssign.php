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

        $getJobTypes = "SELECT EmployeeType, EmployeeSalaryPerDay from employee_type_salary";
        $resultJobType = mysqli_query($conn, $getJobTypes);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {        
            foreach ($_POST['salary'] as $jobType => $salary) {
                $salary = mysqli_real_escape_string($conn, $salary);
                $updateSalaryQuery = "UPDATE employee_type_salary SET EmployeeSalaryPerDay = '$salary' WHERE EmployeeType = '$jobType'";
                mysqli_query($conn, $updateSalaryQuery);
            }

            if ($conn->query($updateSalaryQuery) === TRUE) {
                header("Location: salaryAssign.php");
                
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
                            <form action="salaryAssign.php" method="POST">
                                <table class="table" style = "width : 60%; margin: 0 auto;">
                                    <thead>
                                        <tr>
                                            <th width="35%">Employee Type</th>
                                            <th width = "25%" style = "padding-left:20px;">Salary</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($resultJobType->num_rows > 0) {
                                            while ($row = $resultJobType->fetch_assoc()) {
                                                $jobType = $row['EmployeeType'];
                                                $salary = $row['EmployeeSalaryPerDay'];

                                                echo '<tr>';
                                                echo '<td style="vertical-align: middle; font-size: 16px;;">';
                                                echo $jobType;
                                                echo '</td>';
                                                echo '<td style="vertical-align: middle;">';
                                                echo '<div class="form-group" style="margin-bottom: 0;">';
                                                echo '<input class="form-control" name="salary[' . $jobType . ']" placeholder="" type="number" value = "' .$salary. '">';
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
