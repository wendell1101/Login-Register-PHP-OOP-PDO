<?php
require_once 'config/config.php';
$errors = ['firstname' => '', 'lastname' => '', 'email' => '', 'password1' => '', 'password2' => ''];

$firstname = $lastname = $email = $password1 = $password2 = '';
if (isset($_POST['register'])) {
    // check firstname
    if (empty($_POST['firstname'])) {
        $errors['firstname'] = 'Firstname should not be empty';
    } else {
        $firstname = htmlspecialchars($_POST['firstname']);
    }

    // check lastname
    if (empty($_POST['lastname'])) {
        $errors['lastname'] = 'Lastname should not be empty';
    } else {
        $lastname = htmlspecialchars($_POST['lastname']);
    }

    // check email
    if (empty($_POST['email'])) {
        $errors['email'] = 'Email should not be empty';
    } else {
        $email = htmlspecialchars($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please provide a valid email';
        }
    }

    // check password1
    if (empty($_POST['password1'])) {
        $errors['password1'] = 'Password should not be empty';
    } else {
        $password1 = htmlspecialchars($_POST['password1']);
        $password2 = htmlspecialchars($_POST['password2']);
        // check if passsword1 is equal to password2
        if ($password1 != $password2) {
            $errors['password1'] = 'Passwords do not match. Please try again';
            $errors['password2'] = 'Passwords do not match. Please try again';
        }
    }

    // check password2
    if (empty($_POST['password2'])) {
        $errors['password2'] = 'Confirm-Password should not be empty';
    } else {
        $password2 = htmlspecialchars($_POST['password2']);
    }

    // Check if no more errors
    if (!array_filter($errors)) {
        // check if email already exists
        $sql = "SELECT * FROM users WHERE email=:email LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        if($stmt->rowCount()){
            $errors['email'] = 'Email already exists. Please try a new one';
        }else{
            // hash the password
        $password = md5($password1);
        // save the data to the database
        $sql = "INSERT INTO users (firstname, lastname, email, password) VALUE(:firstname, :lastname, :email, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'password' => $password,
        ]);
        $lastId = $conn->lastInsertId();
        // select the newly registered user and store it in a session
        $sql = "SELECT * FROM users WHERE id=:id";
        $stmt = $conn->prepare($sql);
        $run = $stmt->execute(['id' => $lastId]);
        $user = $stmt->fetch();
        if ($run) {
            $_SESSION['user'] = $user;
            header('Location: index.php');
        }
        }

    }


}

if(isset($_SESSION['user'])){
    header('location: index.php');
}


?>

<?php include('includes/header.php'); ?>
<div class="container">
    <div class="row">
        <div class="col-md-5 mx-auto">
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <h1>Register Here</h1>
                <div class="form-group">
                    <label for="firstname">Firstname</label>
                    <input type="text" name="firstname" id="firstname" value="<?php echo $firstname ?>" class="form-control">
                    <div class="text-danger">
                        <?php echo $errors['firstname']; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastname">Lastname</label>
                    <input type="text" name="lastname" id="lastname" value="<?php echo $lastname ?>" class="form-control">
                    <div class="text-danger">
                        <?php echo $errors['lastname']; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" value="<?php echo $email ?>" class="form-control">
                    <div class="text-danger">
                        <?php echo $errors['email']; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password1">Password</label>
                    <input type="password" name="password1" id="password1" value="<?php echo $password1 ?>" class="form-control">
                    <div class="text-danger">
                        <?php echo $errors['password1']; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password2">Confirm-Password</label>
                    <input type="password" name="password2" id="password2" value="<?php echo $password2 ?>" class="form-control">
                    <div class="text-danger">
                        <?php echo $errors['password2']; ?>
                    </div>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-info" name="register">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include('includes/footer.php') ?>