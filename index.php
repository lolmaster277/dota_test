<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html>
<!--<![endif]-->

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dota</title>

    <!-- Vue -->
    <script src="js/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- Bootstrap  -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

    <link rel="stylesheet" href="css/style.css">
</head>

<body class="mb-5" style="background-color: #17171a;">
    <div id="app" class="container-fluid  text-light">
        <div class="container">
            <h1>Статья которая стоит внимания</h1>
        </div>
        <div class="container">
            <div class="row m-2 p-2 justify-content-between">

                <div class="row">
                    <h3 class="mr-5">Комментарии</h3>
                    <button class="btn btn-dark m-1 " :class="{active: sort === 'new' }" @click="sort='new'">Новые</button>
                    <button class="btn btn-dark m-1 " :class="{active: sort === 'old' }" @click="sort='old'">Старые</button>
                    <button class="btn btn-dark m-1 " :class="{active: sort === 'popular' }" @click="sort='popular'">Популярные</button>
                </div>

                <div class="row">
                    <a href="#" class="btn btn-dark">Правила</a>
                </div>
            </div>
        </div>
        <div class="container">
            <comments-component v-for="comment in comments[0]" @sendcomment="sendCommentPer" :sort="sort" :key="comment.id" :child_comments="comments[comment.id]" :comments="comments" :comment_info="comment"></comments-component>
            <div claass="container">
            <input type="text" class="comments-textarea" placeholder="Ваше имя..." v-model:value="user_name"> 
            <textarea class="comments-textarea" placeholder="Ваш комментарий..." data-tribute="true" v-model:value="comment_text"></textarea>
            <button class="btn btn-dark" @click="addComment()">Отправить</button>
            
        </div>
        </div>
        
    </div>

    <script src="js/app.js"></script>
</body>

</html>