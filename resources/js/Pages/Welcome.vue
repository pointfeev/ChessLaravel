<script setup>
import Layout from '@/Layout/Layout.vue';
import {Link} from '@inertiajs/vue3';
</script>

<template>
    <Layout title='Welcome'>
        <template v-slot:navigation>
            <Link :href="route('play')">Play</Link>
        </template>
    </Layout>
</template>
