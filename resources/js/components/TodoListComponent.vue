<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="">TodoList</div>
                        <div class="d-flex">
                            <input v-model="title" @keyup.enter="createTask" class="form-control mr-2" v-if="show_title"
                                   type="text">
                            <button @click="show_title = !show_title" class="btn btn-primary">+</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="todo-list">
                            <li v-for="(task, index) in reverseTaskLists"
                                class="d-flex justify-content-between align-items-center mb-2">
                                <div :class="{through: task.status}">{{ task.title }}</div>

                                <div class="completed-button">
                                    <button @click="completedTodoTask(index)" class="btn btn-primary">
                                        <svg height="48" viewBox="0 0 48 48" width="48"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0 0h48v48h-48z" fill="none"/>
                                            <path d="M18 32.34l-8.34-8.34-2.83 2.83 11.17 11.17 24-24-2.83-2.83z"/>
                                        </svg>
                                    </button>
                                    <button @click="deleteTodoTask(index)" class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <g>
                                                <path fill="none" d="M0 0h24v24H0z"/>
                                                <path
                                                    d="M4 8h16v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V8zm2 2v10h12V10H6zm3 2h2v6H9v-6zm4 0h2v6h-2v-6zM7 5V3a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v2h5v2H2V5h5zm2-1v1h6V4H9z"/>
                                            </g>
                                        </svg>
                                    </button>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'Todo List',
    data() {
        return {
            task_lists: [],
            title: '',
            show_title: false
        }
    },
    computed: {
        reverseTaskLists() {
            if (typeof this.task_lists !== 'undefined' && this.task_lists.length > 0) {
                return this.sortByKey(this.task_lists, 'id').reverse();
            }
        }
    },
    methods: {
        async loadTasks() {
            axios.get('/tasks').then((response) => {
                this.task_lists = response.data;
            })
        },
        createTask() {
            if (this.title.trim() !== '') {
                this.task_lists.push({title: this.title, status: false});

                axios.post('/tasks', {title: this.title}).then((response) => {
                    this.task_lists.push(response.data);
                })
                this.clearForm();
            }
        },
        completedTodoTask(index) {
            this.task_lists[index].status = true;
            axios.post('/tasks/' + this.task_lists[index].id, {status: true});
        },
        deleteTodoTask(index) {
            let element = this.task_lists[index];
            this.task_lists.splice(index, 1);
            axios.post('/delete-task/' + element.id);
        },
        clearForm() {
            this.title = '';
            this.show_title = false;
        },
        sortByKey(array, key) {
            return array.sort(function (a, b) {
                let x = a[key];
                let y = b[key];
                return ((x < y) ? -1 : ((x > y) ? 1 : 0));
            });
        }
    },

    watch: {
        task_lists(oldValue, newValue) {
            if (this.task_lists.length >= 3) {
                this.task_lists[this.reverseTaskLists.length - (this.reverseTaskLists.length - 1)].status = true;
            }
        }
    },
    mounted() {
        this.loadTasks();

        setInterval(this.loadTasks, 60000);
        console.log('TodoList Component mounted.')
    }
}
</script>

<style lang="scss">
.todo-list {
    li {
        list-style: none;

        .completed-button {
            svg {
                height: 20px;
                width: 20px;
            }
        }

        .through {
            text-decoration: line-through;
        }
    }
}
</style>
