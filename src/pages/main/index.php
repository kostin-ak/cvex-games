<?php
    include_once "utils/account_utils.php";
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/pages/main/home.css">
    <title>Основная страница</title>
</head>
<body>
    <div class="main" style="padding-bottom: 500px;">
        <div class="block_main">
           <div class="block_login">
               <h1>Найдите флаг<b>.</b><b>.</b><b>.</b></h1>
               <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci dignissimos earum error inventore iste
                   labore, laboriosam maxime minus, nemo nulla odit omnis perferendis provident quo recusandae tenetur
                   voluptatum! Culpa, quam.</p>
               <div class="login_buttons <?php echo AccountUtils::is_signed_in()?"hidden":"";?>">
                   <a href="/login"><button>Войти</button></a>
                   <a href="/signup"><button>Регистрация</button></a>
               </div>
           </div>
            <div class="main_picture"><img src="/global/images/main_picture.svg" alt=""></div>
        </div>
        <br><br><br><br><br>
        <div class="tasks-category">
            <div class="task-category"><img src="/global/images/task-category/school.svg"
                                            alt="">
                <h1>Школьники</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab accusantium aliquid at consectetur
                    dignissimos doloremque ea enim facilis impedit iure molestias natus nihil, nisi nulla quaerat
                    quisquam, sapiente soluta temporibus.</p>
                <hr>
            </div>
            <div class="task-category"><img src="/global/images/task-category/student.svg"
                                            alt="">
                <h1>Студенты</h1>
                <p>Adipisci aliquam asperiores autem consequatur corporis culpa cupiditate delectus deleniti dolorum est
                    et exercitationem inventore laboriosam libero maiores neque nesciunt nihil, nostrum numquam possimus
                    sapiente totam ullam unde veritatis vitae?</p>
                <hr>
            </div>
            <div class="task-category"><img src="https://imgholder.ru/512x512/8493a8/adb9ca&textIMAGE+HOLDER&fontkelson"
                                            alt="">
                <h1>Профи</h1>
                <p>Aut cumque deserunt doloremque enim error est eum ex minus natus nisi, obcaecati omnis repellat
                    totam. Asperiores commodi consequuntur deserunt distinctio doloribus esse illo iure libero
                    perspiciatis quo quos, velit.</p>
            </div>
        </div>
    </div>
</body>
</html>