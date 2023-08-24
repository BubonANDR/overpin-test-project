<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Тестовая программа оценки контента </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/vue@2.7.14/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <style>
        .scrollable {
            max-height: 380px;
            overflow: auto;
        }
    </style>

</head>

<body>


    <div class="container mt-2 rounded" id="crudApp">
        <h1 class="text-center mb-3">Оценка картинки</h1>
        <div class="row border rounded pt-2 ps-4 bg-light">
            <div class="card col-4 border-0 bg-light pt-2" style="width: 35rem">
                <img src="./components/pic-1.jpeg" class="card-img-top rounded float-start" alt="..." />
                <div class="card-body">
                    <h6 class="card-title">Природа</h6>
                </div>
            </div>
            <form class="col-6 p-2 ms-3">
                <div class="mb-3">
                    <label class="form-label">Имя пользователя</label>
                    <input v-model="userName" type="text" class="form-control" placeholder="Имя пользователя" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Комментарий</label>
                    <textarea v-model="userComment" class="form-control" rows="7" placeholder="Здесь вы можете оставить комментарий"></textarea>
                </div>
                <div class="row ">

                    <div class="input-group">
                        <button type="button" @click="submitData" class="btn btn-primary mx-1 my-4">
                            Опубликовать комментарий
                        </button>
                        <label class="form-label my-auto mx-1">Введите число на картинке</label>
                        <input type="text" @input="captchaControl" v-model="captchaInput" class="form-control mx-1 my-4" />
                        <img @click="changeCaptcha" class="my-4" :src="captcha" alt='Captcha' />
                    </div>

                </div>
            </form>
        </div>
        <h3 class="mt-3">Комментарии:</h3>
        <div class="scrollable row">
            <ul class="list-group " v-for="row in allData">
                <li class="list-group-item bg-light">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">{{row.user_name}}</h6>
                        <small>{{row.comment_date}}</small>
                    </div>
                    <p class="card-text">
                        {{row.user_comment}}
                    </p>
                    <button type="button" class="btn btn-danger btn-sm position-absolute bottom-0 end-0 m-2" @click="deleteData(row.id)">
                        <img src="./components/trash.svg" />
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
            allData: '',
            userName: "",
            userComment: "",
            commentDate: null,
            captcha: "captcha.php",
            captchaInput: "",
            test: ""
        },
        methods: {
            fetchAllData: function() {
                axios.post('action.php', {
                    action: 'fetchall'
                }).then(function(response) {
                    application.allData = response.data;
                });
            },

            submitData: function() {
                if (application.userName != '' && application.userComment != '' &&
                    application.test != ""
                ) {

                    axios.post('action.php', {
                        action: 'insert',
                        userName: application.userName,
                        userComment: application.userComment
                    }).then(function(response) {
                        application.fetchAllData();
                        application.userName = '';
                        application.userComment = '';
                        alert(response.data.message);
                        application.captchaInput = "";
                        application.changeCaptcha();
                    });
                } else {
                    alert("Заполните все поля!");
                }
            },

            deleteData: function(id) {
                if (confirm("Вы точно хотите ударить комментарий?")) {
                    axios.post('action.php', {
                        action: 'delete',
                        id: id
                    }).then(function(response) {
                        application.fetchAllData();
                        alert(response.data.message);
                    });
                }
            },
            changeCaptcha: function() {
                application.captcha = "captcha.php?any=" + new Date().getTime();
            },

            captchaControl: function() {
                axios.post('test.php', {
                    action: 'control',
                    captchaInput: application.captchaInput
                }).then(function(response) {

                    application.test = response.data;
                });

            }

        },
        created: function() {
            this.fetchAllData();

        }
    });
</script>