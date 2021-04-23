<?php
//    Check if there is a request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone_pattern = "/^(\+2)?01[0-2]\d{1,8}$/i";

    $user = array(
        'f_name' => filter_var($_POST['f_name'], FILTER_SANITIZE_STRING),
        'l_name' => filter_var($_POST['l_name'], FILTER_SANITIZE_STRING),
        'username' => filter_var($_POST['username'], FILTER_SANITIZE_STRING),
        'email' => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
        'phone' => filter_var($_POST['phone'], FILTER_SANITIZE_STRING),
        'message' => filter_var($_POST['message'], FILTER_SANITIZE_STRING)
    );

//    errors
    $errors = array(
        'f_name' => strlen($user['f_name']) > 3,
        'l_name' => strlen($user['l_name']) > 3,
        'username' => strlen($user['username']) > 4,
        'email' => filter_var($user['email'], FILTER_VALIDATE_EMAIL),
        'phone' => preg_match($phone_pattern, $user['phone']),
        'message' => strlen($user['message']) > 15
    );

    $find_errors = in_array(false, $errors);

    if (!$find_errors) {
        $headers = 'From: ' . $user['email'] . '\r\n';
        $mail_to = '<admin email>';
        $subject = 'Contact From <site url>';
        $message = 'Name: ' . $user['f_name'] . ' ' . $user['l_name'] . "\r\n" . $user['message'];

        try {
            @mail($mail_to, $subject, $user['message'], $headers);
            $errors = array_fill_keys(array_keys($errors), "");
            $success = true;
        } catch (Exception $e) {
            echo $e->getMessage();
            $success = false;
        }

    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contact Form</title>
    <!--  Line Awesome  -->
    <!--    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">-->
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
</head>
<body>
<div class="container py-5">
    <h1 class="text-center">Contact Form</h1>
    <hr class="mx-5 mb-5">
    <div class="row">
        <div class="col-sm-11 col-12 mx-auto">
            <?php if (isset($success) && $success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <p class="m-0 text-center">Thanks for contact us</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif (isset($success) && !$success): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <p class="m-0 text-center">There is an error, please try again</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" class="row g-3 needs-validation" novalidate>
                <div class="col-md-4">
                    <label for="validationCustom01" class="form-label">First name</label>
                    <input name="f_name" type="text" value="<?php if (isset($find_errors) && $find_errors) echo $user['f_name']; ?>"
                           class="form-control <?php if (isset($find_errors) && $find_errors) echo($errors['f_name'] ? '' : 'is-invalid'); ?> "
                           id="validationCustom01" pattern=".{3,}" required>
                    <div class="invalid-feedback">
                        Please provide your first name.
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="validationCustom02" class="form-label">Last name</label>
                    <input name="l_name" type="text" value="<?php if (isset($find_errors) && $find_errors) echo $user['l_name']; ?>"
                           class="form-control  <?php if (isset($find_errors) && $find_errors) echo($errors['l_name'] ? '' : 'is-invalid'); ?> "
                           id="validationCustom02" pattern=".{3,}"
                           required>
                    <div class="invalid-feedback">
                        Please provide your last name.
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="validationCustomUsername" class="form-label">Username</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input name="username" type="text" value="<?php if (isset($find_errors) && $find_errors) echo $user['username']; ?>"
                               class="form-control <?php if (isset($find_errors) && $find_errors) echo($errors['username'] ? '' : 'is-invalid'); ?> "
                               id="validationCustomUsername"
                               aria-describedby="inputGroupPrepend" required>
                        <div class="invalid-feedback">
                            Please write valid username.
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="validationCustom03" class="form-label">Email</label>
                    <input name="email" type="email" value="<?php if (isset($find_errors) && $find_errors) echo $user['email']; ?>"
                           class="form-control <?php if (isset($find_errors) && $find_errors) echo($errors['email'] ? '' : 'is-invalid'); ?> "
                           id="validationCustom03" required>
                    <div class="invalid-feedback">
                        Please provide a valid email.
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="validationCustom04" class="form-label">Phone</label>
                    <input name="phone" type="text" pattern="^(\+2)?01[0-2]\d{1,8}$"
                           value="<?php if (isset($find_errors) && $find_errors) echo $user['phone']; ?>"
                           class="form-control <?php if (isset($find_errors) && $find_errors) echo($errors['phone'] ? '' : 'is-invalid'); ?> "
                           id="validationCustom04" required>
                    <div class="invalid-feedback">
                        Please provide a valid phone number.
                    </div>
                </div>
                <div class="col-md-12">
                    <label for="validationCustom05" class="form-label">Message</label>
                    <textarea name="message"
                              class="form-control <?php if (isset($find_errors) && $find_errors) echo($errors['message'] ? '' : 'is-invalid'); ?> "
                              id="validationCustom05" required minlength="20"
                              rows="4"><?php if (isset($find_errors) && $find_errors) echo($user['message']); ?></textarea>
                    <div class="invalid-feedback">
                        Message input is required (min is 15 char).
                    </div>
                </div>

                <div class="col-12">
                    <button name="submit" class="btn btn-primary" type="submit">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Jquery -->
<!--    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>-->
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf"
        crossorigin="anonymous"></script>
<!-- custom script   -->
<script src="js/script.js"></script>
</body>
</html>