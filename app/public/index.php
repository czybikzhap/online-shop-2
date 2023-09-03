<?php
if($_SERVER['REQUEST_METHOD'] === "POST") {

    $errors = IsValidSignUp($_POST);

    if (empty($errors)) {

        $pdo = new PDO('pgsql:host=db; dbname=dbname', 'dbuser', 'dbpwd');

        $name = $_POST['name'];
        $email = $_POST['email'];
        $pwd = $_POST['password'];

        $pwd = password_hash($pwd, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) 
                VALUES (:name, :email, :pwd)");
        $stmt->execute(['name' => $name, 'email' => $email, 'pwd' => $pwd]);
    }
}

function IsValidSignUp(array $data): array
{
    $errors = [];

    $pdo = new PDO('pgsql:host=db; dbname=dbname', 'dbuser', 'dbpwd');

    if (!isset($data['name'])) {
        $errors['name'] = 'поле имя не задано';
    } else {
        $name = $data['name'];
        if (empty($name)) {
            $errors['name'] = "поле имя не может быть пустым";
        }
        if (strlen($name) < 2) {
            $errors['name'] = "имя должно содержать больше 2 символов";
        }
        for ($i = 1; $i <= 9; $i++) {
            if (str_contains($name, $i)) {
                $errors['name'] = "имя не должно содержать цифры";
            }
        }
    }
    if (!isset($data['email'])) {
        $errors['email'] = "поле email не задано";
    } else {
        $email = $data['email'];
        if (empty($email)) {
            $errors['email'] = "поле email не может быть пустым";
        }
        if (strlen($email) < 3) {
            $errors['email'] = "email должен содержать больше 3 символов";
        }
        if (!str_contains($email, '@')) {
            $errors['email'] = "некорректный email";
        }

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email ");
        $stmt->execute(['email' => $email]);
        $userData = $stmt->fetch();
        if (!empty($userData['email'])) {
            $errors['email'] = 'пользователь с таким адресом электронной почты уже зарегистрирован';
        }
    }
    if (!isset($data['password'])) {
        $errors['password'] = "поле пароля не задано";
    } else {
        $pwd = $data['password'];
        if (empty($pwd)) {
            $errors['password'] = "поле пароля не может быть пустым";
        }
        if (strlen($pwd) < 3) {
            $errors['password'] = "пароль должен содержать больше 3 символов";
        }
        if ($pwd !== $_POST['confirm-password']) {
            $errors['password'] = "пароли не совпадают";
        }
    }
    return $errors;
}


?>


<body>
<div id="container">
    <header>Register new account</header>
    <form method="post">
        <fieldset>
            <br/>
            <input type="text" name="name" id="name" placeholder="Username" >
            <br/>
            <label style="color:red">
                <?php if (!empty($errors['name'])) {
                    echo $errors['name'];
                } ?>
            </label>
            <br/>
            <input type="text" name="email" id="email" placeholder="E-mail" >
            <br/>
            <label style="color:red">
                <?php if (!empty($errors['email'])) {
                    echo $errors['email'];
                } ?>
            </label>
            <br/>
            <input type="password" name="password" id="password" placeholder="Password" >
            <br/>
            <label style="color:red">
                <?php if (!empty($errors['password'])) {
                    echo $errors['password'];
                } ?>
            </label>
            <br/>
            <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirm Password" >
            <br/>
            <br/> <br/>
            <label for="submit"></label>
            <input type="submit" name="submit" id="submit" value="REGISTER">
        </fieldset>
    </form>
</div>
</body>

<style>
    html {
        height: 100%;
        width: 100%;
    }

    body {
        background: url("https://pw.artfile.me/wallpaper/30-10-2020/650x366/anime-naruto-uchiha-madara-mech-boj-1534114.jpg") no-repeat center center fixed;
        background-size: cover;
        font-family: 'Droid Serif', serif;
    }

    #container {
        background: rgba(3, 3, 55, 0.5);
        width: 18.75rem;
        height: 25rem;
        margin: auto;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }

    header {
        text-align: center;
        vertical-align: middle;
        line-height: 3rem;
        height: 3rem;
        background: rgba(3, 3, 55, 0.7);
        font-size: 1.4rem;
        color: #d3d3d3;
    }

    fieldset {
        border: 0;
        text-align: center;
    }

    input[type="submit"] {
        background: rgba(235, 30, 54, 1);
        border: 0;
        display: block;
        width: 70%;
        margin: 0 auto;
        color: white;
        padding: 0.7rem;
        cursor: pointer;
    }

    input {
        background: transparent;
        border: 0;
        border-left: 4px solid;
        border-color: #FF0000;
        padding: 10px;
        color: white;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus {
        outline: 0;
        background: rgba(235, 30, 54, 0.3);
        border-radius: 1.2rem;
        border-color: transparent;
    }

    ::placeholder {
        color: #d3d3d3;
    }

    /*Media queries */

    @media all and (min-width: 481px) and (max-width: 568px) {
        #container {
            margin-top: 10%;
            margin-bottom: 10%;
        }
    }
    @media all and (min-width: 569px) and (max-width: 768px) {
        #container {
            margin-top: 5%;
            margin-bottom: 5%;
        }
    }
</style>