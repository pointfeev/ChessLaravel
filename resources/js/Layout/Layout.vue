<script setup>
import {Head} from '@inertiajs/vue3';

defineProps(['title']);
</script>

<template>
    <Head :title='title'/>
    <div class='w-screen h-screen flex flex-col'
         v-bind='$attrs' v-on='$listeners'>
        <header v-if='$slots.header' class='flex-initial p-4 flex gap-2 border-b border-slate-200/10 shadow'>
            <slot name='header'/>
        </header>

        <main v-if='$slots.default' class='flex-1 p-8'>
            <slot/>
        </main>

        <footer v-if='$slots.footer' class='flex-initial p-4'>
            <slot name='footer'/>
        </footer>
    </div>
</template>
