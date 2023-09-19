<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="author" content="Muhamad Nauval Azhar">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="This is a login page template based on Bootstrap 5">
    <title>Bootstrap 5 Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
</head>

<body>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">

        <section class="h-100">

            <div class="container h-100">
                <div class="row justify-content-sm-center h-100 mt-5">
                    <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9 mt-5">
                        <div class="card shadow-lg">
                            <div class="text-center my-5 mb-2">
                                <img src="img/logo.png" alt="logo" width="200">
                            </div>

                            <div class="card-body p-5">
                                <?php

                                if ($_GET) {
                                    if ($_GET["action"] == "success") {
                                        echo "<div class='alert alert-success'>{$_GET['message']}</div>";
                                    } else if ($_GET['action'] == "warning") {
                                        echo "<div class='alert alert-danger'>Please login first</div>";
                                    }
                                }

                                if ($_POST) {
                                    include 'config/database.php';
                                    $username = $_POST["username"];
                                    $password = $_POST["password"];

                                    if (!empty($username) && !empty($password)) {
                                        try {
                                            // select all data for verify username
                                            $query = "SELECT * FROM customers WHERE username=:username";
                                            $stmt = $con->prepare($query);

                                            $stmt->bindParam(':username', $username);
                                            $stmt->execute();

                                            // this is how to get number of rows returned
                                            $num = $stmt->rowCount();

                                            if ($num > 0) {
                                                // retrieve our table contents
                                                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                                if (md5($password) == $row["password"]) {
                                                    if ($row["account_status"] != "Inactive") {

                                                        // Storing username of the logged in user,
                                                        // in the session variable
                                                        $_SESSION['username'] = $username;

                                                        // Welcome message
                                                        $_SESSION["msg"] = "Login Successful";

                                                        // Page on which the user will be
                                                        // redirected after logging in
                                                        $url = "dashboard.php";
                                                        header('Location: ' . $url);
                                                        die();
                                                    } else {
                                                        echo "<div class='alert alert-danger'>Account has been suspended!</div>";
                                                    }
                                                } else {
                                                    echo "<div class='alert alert-danger'>Please make sure username and password are correct!</div>";
                                                }
                                            } else {
                                                echo "<div class='alert alert-danger'>User not exist!</div>";
                                            }
                                        } catch (PDOException $exception) {
                                            die('ERROR: ' . $exception->getMessage());
                                        }
                                    } else {
                                        echo "<div class='alert alert-danger'>Please make sure fields are not empty!</div>";
                                    }
                                }
                                ?>
                                <h1 class="fs-4 card-title fw-bold mb-4">Login</h1>
                                <form method="POST" class="needs-validation" novalidate="" autocomplete="off">
                                    <div class="mb-3">
                                        <label class="mb-2 text-muted" for="username">Username</label>
                                        <input id="username" type="text" class="form-control" name="username" value="" autofocus>
                                        <div class="invalid-feedback">
                                            Email is invalid
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="mb-2 w-100">
                                            <label class="text-muted" for="password">Password</label>
                                        </div>
                                        <input id="password" type="password" class="form-control" name="password">
                                        <div class="invalid-feedback">
                                            Password is required
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <button type="submit" class="btn btn-primary ms-auto">
                                            Login
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer py-3 border-0">
                                <div class="text-center">
                                    Don't have an account? <a href="customer_create.php" class="text-primary text-decoration-none">Create One</a>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-5 text-muted">
                            Copyright &copy; 2022 &mdash; Bellerose
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>

    <script src="js/login.js"></script>
</body>

</html>