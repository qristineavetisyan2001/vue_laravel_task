import { createStore } from 'vuex';

export default createStore({
    state: {
        tasks: [],
    },
    mutations: {
        setTasks(state, tasks) {
            state.tasks = tasks;
        },
    },
    actions: {
        async fetchTasks({ commit }) {
            try {
                const response = await fetch('http://127.0.0.1:8000/api/tasks');
                const tasks = await response.json();
                commit('setTasks', tasks);
            } catch (error) {
                console.error('Error fetching tasks:', error);
            }
        },
    },
    modules: {},
});
