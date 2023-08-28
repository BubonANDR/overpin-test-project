<!DOCTYPE html>
<html data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Тестовая программа оценки контента </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/vue@2.7.14/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <style>
        .scrollable {
            max-height: 360px;
            overflow: auto;
        }
    </style>

</head>

<body>
    <header class="">
        <h1 class="text-center mb-3">Оценка картинки</h1>
    </header>

    <div class="container mt-2 rounded " id="crudApp">


        <div class="row border rounded pt-2 ps-4 bg-body-tertiary shadow-lg">
            <div class="card col-4 border-0 bg-body-tertiary  pt-2" style="width: 35rem">
                <img src="../images/pic-1.jpeg" class="card-img-top rounded float-start" alt="..." />
                <div class="card-body">
                    <h6 class="card-title">Природа</h6>
                </div>
            </div>
            <form class="col-6 p-2 ms-3">
                <div class="mb-3">
                    <label class="form-label">Имя пользователя</label>
                    <input v-model="userName" type="text" class="form-control text-dark bg-light" placeholder="Имя пользователя" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Комментарий</label>
                    <textarea v-model="userComment" class="form-control bg-light text-dark" rows="7" placeholder="Здесь вы можете оставить комментарий"></textarea>
                </div>
                <div class="row ">

                    <div class="input-group input-group-sm">
                        <button type="button" @click="submitData" class="btn btn-primary rounded">
                            Опубликовать комментарий
                        </button>
                        <input type="text" v-model="captchaInput" class="form-control mx-2 my-2 bg-light text-dark rounded" placeholder="КОД" />
                        <label class="form-label mx-2 py-2">введите код </label>
                        <img @click="changeCaptcha" class="object-fit-fill " style=" height: 55px;" :src="captcha" alt='Captcha' />
                    </div>

                </div>
            </form>
        </div>
        <h3 class="mt-3">Комментарии:</h3>
        <div class="scrollable row">
            <ul class="list-group " v-for="row in allData" :key="row.id">

                <li class="list-group-item bg-body-tertiary  mb-1 shadow ">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1 text-warning-emphasis">{{row.user_name}}</h6>
                        <small>{{row.comment_date}}</small>
                    </div>
                    <p class="card-text">
                        {{row.user_comment}}
                    </p>
                    <button type="button" class="btn btn-danger btn-sm position-absolute bottom-0 end-0 m-2" @click="deleteData(row.id)">
                        <img src="../images/trash.svg" />
                    </button>
                </li>
            </ul>
        </div>
    </div>



</body>

</html>

<script>
    const application = new Vue({
        el: '#crudApp',
        data: {
            allData: "",
            userName: "",
            userComment: "",
            commentDate: null,
            captcha: "",
            captchaInput: "",
            captchacode: "wzasexdrcftvg",
        },

        methods: {
            fetchAllData: function() {
                axios.get('/src/public/index.php/comments')
                    .then(function(response) {
                        application.allData = response.data;
                        application.changeCaptcha();
                    });
            },

            submitData: function() {

                if (application.userName != '' && application.userComment != '') {
                    if (application.captchaInput == application.captchacode) {
                        axios.post('/src/public/index.php/comment', {
                            name: application.userName,
                            comment: application.userComment
                        }).then(function(response) {
                            application.fetchAllData();
                            application.userName = '';
                            application.userComment = '';
                            application.captchaInput = "";
                        });
                    } else {
                        alert("Введите верный код с картинки!");
                    }

                } else {
                    alert("Заполните все поля!");
                }
            },

            deleteData: function(id) {
                if (confirm("Вы точно хотите ударить комментарий?")) {
                    axios.post('/src/public/index.php/comment', {
                        id: id
                    }).then(function(response) {
                        application.fetchAllData();
                    });
                }
            },
            changeCaptcha: function() {
                axios.get('/src/public/index.php/captcha')
                    .then(function(response) {
                        application.captcha = response.data.captcha;
                        application.captchacode = response.data.code;
                    });
            },

        },
        created: function() {
            this.fetchAllData();
        },

    });
</script>