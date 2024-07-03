<script setup>
import Layout from '../Layout.vue'
import { Head } from '@inertiajs/vue3'

defineProps({ proxies: Array, task: Object })
</script>

<template>
    <Layout>
        <Head :title="'Proxylist #' + task.id" />

        <h1>Proxylist #{{task.id}}</h1>

        <table class="table">
            <thead>
            <tr>
                <th>Proxy</th>
                <th>Status</th>
                <th>Type</th>
                <th>Location</th>
                <th>Timeout</th>
                <th>Real IP</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="proxy in proxies">
                <td>{{ proxy.ip }}</td>
                <td>
                    <span v-if="proxy.status">enabled</span>
                    <span v-else>disabled</span>
                </td>
                <td>
                    <span v-if="proxy.status">
                        <template v-if="proxy.proxy_type === 2">HTTPS</template>
                        <template v-else-if="proxy.proxy_type === 0">HTTP</template>
                        <template v-else-if="proxy.proxy_type === 4">SOCKS4</template>
                        <template v-else-if="proxy.proxy_type === 5">SOCKS5</template>
                        <template v-else>-</template>
                    </span>
                </td>
                <td>
                    {{ proxy.location }}
                </td>
                <td>
                    {{ proxy.timeout }}
                </td>
                <td>
                    {{ proxy.real_ip }}
                </td>
            </tr>
            </tbody>
        </table>
    </Layout>
</template>
