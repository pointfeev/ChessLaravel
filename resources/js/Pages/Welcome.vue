<script setup>
import Layout from '@/Layouts/Layout.vue';
import NavLink from '@/Components/Layout/NavLink.vue';
</script>

<template>
    <Layout title='Welcome'>
        <!-- TODO: convert to full page option menu -->

        <template v-slot:header>
            <NavLink :href="route('play')">Play</NavLink>
        </template>
    </Layout>
</template>
