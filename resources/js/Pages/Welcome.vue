<script setup>
import Layout from '@/Layout/Layout.vue';
import NavLink from '@/Components/Layout/NavLink.vue';
</script>

<template>
    <Layout title='Welcome'>
        <template v-slot:header>
            <NavLink :href="route('play')">Play</NavLink>
        </template>
    </Layout>
</template>
