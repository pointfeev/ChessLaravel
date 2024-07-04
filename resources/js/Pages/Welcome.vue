<script setup>
import Layout from '@/Layouts/Layout.vue';
import NavLink from '@/Components/Layout/NavLink.vue';
</script>

<template>
    <Layout title='Welcome'>
        <div class='w-full h-full flex items-center justify-center'>
            <div class='bg-slate-300/10 rounded-xl shadow p-2 flex flex-col gap-2'>
                <NavLink :href="route('play')">Play</NavLink>
                <NavLink href='#'>Options</NavLink>
            </div>
        </div>
    </Layout>
</template>
