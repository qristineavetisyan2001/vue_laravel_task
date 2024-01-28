<template>
    <div>
        <h1>Tasks</h1>
        <button @click="openAddTaskModal">add</button>
        <table>
            <thead>
            <tr>
                <th>id</th>
                <th>title</th>
                <th>text</th>
                <th>status</th>
                <th>actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(task) of taskTable.tasks">
                <td>{{ task.id }}</td>
                <td>{{ task.title }}</td>
                <td>{{ task.text }}</td>
                <td>
                    <select @change="editTaskStatus(task.id)" v-model="editStatus[task.id]" name="" id="">
                        <option :selected="key===task.status" v-for="key in taskTable.statusEnum" :value="key">
                            {{ key }}

                        </option>
                    </select>
                </td>
                <td>
                    <div>
                        <button @click="openDeleteTaskModal(task.id)">Delete</button>
                        <button @click="openEditTaskModal(task)">Edit</button>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <div v-if="showAddTaskModal" class="modal">
            <div class="modal-content">
                <span class="close" @click="closeAddTaskModal">&times;</span>
                <input v-model="newTask.title" placeholder="title"/>
                <textarea v-model="newTask.text" name="" id="" cols="30" rows="10"></textarea>
                <button @click="saveNewTask">Save Changes</button>
            </div>
        </div>
        <div v-if="showEditTaskModal" class="modal">
            <div class="modal-content">
                <span class="close" @click="closeEditTaskModal">&times;</span>
                <input v-model="editTaskData.title"  placeholder="Edit task"/>
                <input v-model="editTaskData.text"  placeholder="Edit task"/>
                <button @click="editTask(editTaskData.id)">Save Changes</button>
            </div>
        </div>
        <div v-if="showDeleteModal" class="modal">
            <div class="modal-content">
                CONFIRM DELETE
                <button @click="closeDeleteTaskModal">NO</button>
                <button @click="deleteTask(deleteTaskId)">YES</button>
            </div>
        </div>
    </div>
</template>

<script>
import {computed, onMounted} from 'vue';
import {useStore} from 'vuex';
import axios from "axios";
export default {
    name: 'App',
    setup() {
        const store = useStore();
        const taskTable = computed(() => store.state.tasks);
        onMounted(() => {
            store.dispatch('fetchTasks');
            this.tasks.forEach(task => {
                this.$set(this.editStatus, task.status);
            })
        });
        return {
            taskTable,
        };
    },
    data() {
        return {
            showEditTaskModal: false,
            showAddTaskModal: false,
            showDeleteModal: false,
            newTask: {},
            editTaskData: {},
            editStatus: [],
            deleteTaskId: null
        }
    },
    methods: {
        remove(index) {
            console.log(index)
            return this.tasks.splice(index, 1)
        },
        openEditTaskModal(task) {
            this.editTaskData = task;

            this.showEditTaskModal = true;
        },
        closeEditTaskModal() {
            this.showEditTaskModal = false;
        },
        openAddTaskModal() {
            this.showAddTaskModal = true;
        },
        closeAddTaskModal() {
            this.showAddTaskModal = false;
        },
        openDeleteTaskModal(id) {
            this.deleteTaskId = id;
            this.showDeleteModal = true;
        },
        closeDeleteTaskModal() {
            this.showDeleteModal = false;
        },
        async saveNewTask() {
            await fetch("http://127.0.0.1:8000/api/tasks", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify(this.newTask)
            }).then(res => res.json()).then(res=>{
                this.taskTable.tasks.push(res);
                console.log(res)
            })

            this.closeAddTaskModal();
        },
        async deleteTask(id){
            await fetch("http://127.0.0.1:8000/api/tasks/" + id, {
                method: 'DELETE',
            }).then(res => res.json()).then(res=>{
                console.log(res)
            })
            this.taskTable.tasks = this.taskTable.tasks.filter( task=>task.id !== id);
            this.closeDeleteTaskModal();
        },
        async editTask(id){
            await axios.put("http://127.0.0.1:8000/api/tasks/" + id,
                 this.editTaskData
            )
            this.closeEditTaskModal();
        },
        async editTaskStatus(id){
            await axios.put("http://127.0.0.1:8000/api/tasks/edit-task-status/" + id,
                {status: this.editStatus[id]}
            )
        }
    }
};
</script>

<style scoped>
.modal {
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
    padding-top: 60px;
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
</style>
