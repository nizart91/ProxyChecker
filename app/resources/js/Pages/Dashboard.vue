<script setup>
import Layout from '../Layout.vue'
import { Head } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'

// defineProps({ tasks: Array })
</script>

<script>

export default {
    data() {
        return {
            text: '',
            timer: null,
            tasks: []
        }
    },
    async created() {
        await this.index();
    },
    beforeUnmount() {
        if (this.timer !== null){
            clearInterval(this.timer)
        }
    },
    methods: {
        async index() {
            this.tasks = (await axios.get('tasks')).data.data;

            const work = this.tasks.filter(task => Number(task.total) > Number(task.finished));
            if (work.length <= 0){
                if (this.timer !== null){
                    clearInterval(this.timer)
                }
            }
            else if (!this.timer){
                this.timer = setInterval(async () => await this.index(), 5000);
            }
        },
        async save() {
            try {
                await axios.post('tasks',{
                    ips: this.text.split("\n")
                });
                this.text = '';
                await this.index();
            } catch (err) {
                let error = 'Error'
                if (err.response && err.response.data.message) {
                    error = err.response.data.message
                }
                alert(error);
            }
        }
    },
    computed:{
        rows() {
            return this.tasks.map((task) => {
                task.time = null;

                if (task.total == task.finished) {
                    // Создание объектов Date для двух дат
                    const date1 = new Date(task.created_at);
                    const date2 = new Date(task.updated_at);

                    // Разница в миллисекундах между двумя датами
                    const diffMs = Math.abs(date2 - date1);

                    // Преобразование разницы в секунды
                    const diffSeconds = Math.floor(diffMs / 1000);

                    // Преобразование разницы в минуты и секунды
                    const diffMinutes = Math.floor(diffSeconds / 60);
                    const remainderSeconds = diffSeconds % 60;

                    // Преобразование разницы в часы, минуты и секунды
                    const diffHours = Math.floor(diffMinutes / 60);
                    const remainderMinutes = diffMinutes % 60;

                    // Форматирование вывода времени выполнения
                    task.time = `${diffHours}:${remainderMinutes}:${remainderSeconds}`;
                }

                return task;
            })
        },
    }
}
</script>

<template>
    <Layout>
        <Head title="Proxy checker" />
        <h1>Proxy checker</h1>

        <form @submit.prevent="save">
            <div class="form-group">
                <label for="exampleFormControlTextarea1">Proxy list</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" v-model="text"></textarea>
            </div>

            <button class="btn btn-success" type="submit">Check</button>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Time</th>
                    <th>Progress</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="task in rows">
                    <td>
                        <Link :href="'/tasks/' + task.id">Proxylist #{{ task.id }}</Link>
                    </td>
                    <td>{{ task.time }}</td>
                    <td>
                        <div class="progress">
                            <div class="progress-bar"
                                 role="progressbar"
                                 :style="{
                                     width: `${task.finished / task.total * 100}` + '%'
                                 }"
                                 :aria-valuenow="task.finished"
                                 aria-valuemin="0"
                                 :aria-valuemax="task.total"
                            > {{ task.finished }} / {{ task.total }}</div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </Layout>
</template>
