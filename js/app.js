Vue.component('comments-component', {
    props: ['child_comments', 'comment_info', 'comments'],
    data: function() {
        return {
            reply: false,
            comment_text: "",
            user_name: "",
            like_stat: 0,
        }
    },
    template: `
    <div class="container p-4"   :class="{subcomment: comment_info.parent_id!=0}">
    <div class="row pl-3">
        <div ><img src="img/ava.png" width="50px" height="50px" class="rounded-circle"></div>
        <div class="row col-11">
            <div class="col-12" style="color:#797983">
                <ul class="list-inline">
                    <li class="list-inline-item"><b>{{comment_info.user_name}}</b></li>
                    <li class="list-inline-item"><i class="d-inline far fa-clock"></i> {{comment_info.date}}</li>
                    <li class="list-inline-item"><a class="btn" @click="addComment()"><i class="fas fa-reply"></i> Ответить</a></li>
                    
                    <li class="list-inline-item float-right">{{comment_info.likes}}</li>
                    <li class="list-inline-item float-right"><a class="btn" :class="{'text-danger':like_stat===2}" @click="dislike()"><i class="fas fa-thumbs-down"></i></a></li>
                    <li class="list-inline-item float-right"><a class="btn" :class="{'text-success':like_stat===1}" @click="like()"><i class="fas fa-thumbs-up"></i></a></li>
                </ul>
                
                 
            </div>
            <div class="col-12"><p>{{comment_info.text}}</p></div>
    
        </div>
        
    </div>
    <div v-show="reply">
        <input type="text" class="comments-textarea" placeholder="Ваше имя..." v-model:value="user_name"> 
        <textarea class="comments-textarea" placeholder="Ваш комментарий..." data-tribute="true" v-model:value="comment_text"></textarea>
        <button class="btn btn-dark" @click=sendComment(comment_info.id)>Отправить</button>
        <button class="btn btn-dark" @click="close()">X</button>
    </div>
    <comments-component @sendcomment="sendCommentPer"  v-for="comment in child_comments" :key="comment.id" :child_comments="comments[comment.id]" :comment_info="comment" :comments="comments"></comments-component>
    </div>
    `,
    methods: {
        addComment: function() {
            this.reply = true;
        },
        close: function() {
            this.reply = false;
        },
        sendComment: function(index) {
            this.reply = false;

            this.$emit('sendcomment', index, this.user_name, this.comment_text);
            this.user_name = "";
            this.comment_text = "";
        },
        sendCommentPer: function(index, user_name, comment_text) {
            this.$emit('sendcomment', index, user_name, comment_text);
        },
        like: function() {
            if (this.like_stat != 1) {
                this.comment_info.likes = this.like_stat == 2 ? Number(this.comment_info.likes) + 2 : Number(this.comment_info.likes) + 1;
                this.like_stat = 1;

                axios.post('php/like.php', {
                    commId: this.comment_info.id,
                }).then(function(response) {
                    data = response.data;

                    if (data.status != 0) {
                        alert(data.message);
                    }

                });
            }
        },
        dislike: function() {
            if (this.like_stat != 2) {
                this.comment_info.likes = this.like_stat == 1 ? Number(this.comment_info.likes) - 2 : Number(this.comment_info.likes) - 1;
                this.like_stat = 2;

                axios.post('php/dislike.php', {
                    commId: this.comment_info.id,
                }).then(function(response) {
                    data = response.data;
                    if (data.status != 0) {
                        alert(data.message);
                    }

                });
            }
        }
    },
    mounted() {
        /*
                let date = Date.parse(new Date()) - Date.parse(this.comment_info.date);
                if (date / 1000 < 60) {
                    this.comment_info.date = Math.floor(date / 1000) + " секунд назад";
                } else {
                    if (date / 1000 / 60 < 60) {
                        this.comment_info.date = Math.floor(date / 60000) + " минут назад";
                    } else {
                        if (date / 1000 / 60 / 60 < 24) {
                            this.comment_info.date = Math.floor(date / 3600000) + " часов назад";
                        }
                    }
                }*/
    }

});

const app = new Vue({
    el: '#app',
    data: {
        comments: [],
        sort: 'new',
        user_name: "",
        comment_text: "",
    },
    methods: {
        getComments: function() {
            axios.post('php/getComments.php', {
                postId: 12,
            }).then(function(response) {
                data = response.data;
                if (data.status != 0) {
                    alert(data.message);
                } else {
                    app.comments = data.comments;
                }

            });
        },
        addComment: function() {

            let app = this;
            axios.post('php/addComment.php', {
                postId: 12,
                commentId: 0,
                user_name: this.user_name,
                comm_text: this.comment_text,
                sort: this.sort,
            }).then(function(response) {
                data = response.data;

                app.user_name = "";
                app.comment_text = "";
                if (data.status != 0) {
                    alert(data.message);
                } else {

                    Vue.set(app.comments, 0, data.comments[0]);


                }

            });
        },
        sendCommentPer: function(index, user_name, comment_text) {
            let app = this;
            axios.post('php/addComment.php', {
                postId: 12,
                commentId: index,
                user_name: user_name,
                comm_text: comment_text,
                sort: this.sort,
            }).then(function(response) {
                data = response.data;
                if (data.status != 0) {
                    alert(data.message);
                } else {
                    Vue.set(app.comments, index, data.comments[index]);


                }

            });

        },


    },
    mounted() {
        this.getComments();
    },
    watch: {
        sort: function(newVal, oldVal) {
            if (newVal == 'new') {
                for (let i = 0; i < Object.keys(app.comments).length; i++) {
                    app.comments[Object.keys(app.comments)[i]].sort((a, b) => b.date > a.date)
                }
            } else {
                if (newVal == 'old') {
                    for (let i = 0; i < Object.keys(app.comments).length; i++) {
                        app.comments[Object.keys(app.comments)[i]].sort((a, b) => b.date < a.date)
                    }
                } else {
                    for (let i = 0; i < Object.keys(app.comments).length; i++) {
                        app.comments[Object.keys(app.comments)[i]].sort((a, b) => b.likes - a.likes)
                    }
                }
            }
        }
    }
})