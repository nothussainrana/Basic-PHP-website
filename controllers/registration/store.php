<?php

use Core\Database;
use Core\Validator;
use Core\App;

$email = $_POST['email'];
$password = $_POST['password'];

// validate the form
if (!Validator::email($email)) {
    $errors['email'] = 'Please provide a valid email address';
}

if (!Validator::string($password, 7, 255)) {
    $errors['password'] = 'Please provide a password of at least 7 characters';
}

if (!empty($errors)) {
    return view('registration/create.view.php', ['errors' => $errors]);
}

$db = App::resolve(Database::class);

$user = $db->query('select * from users where email = :email', [
    'email' => $email
])->find();

if ($user) {
    header('location: /');
    exit();
} else {
    $db->query('insert into users (email, password) VALUES (:email, :password)', [
        'email' => $email,
        'password' => $password
    ]);
    // mark that the user has logged in.
    $_SESSION['user'] = [
        'email' => $email,
    ];

    header('location: /');
    exit();
}

// check if the email exists
    // if yes, redirect to a login page
    // if no, save one to the database, and then log the user in, and redirect