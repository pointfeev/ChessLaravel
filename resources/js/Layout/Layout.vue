<script setup>
import {Head} from '@inertiajs/vue3';

defineProps(['title']);
</script>

<template>
    <Head :title='title'/>
    <div class='w-screen h-screen flex flex-col' v-bind='$attrs'>
        <div v-if='$slots.navigation' class='flex-initial'>
            <slot name='navigation'/>
        </div>
        <div v-if='$slots.default' class='flex-1 p-10'>
            <slot/>
        </div>
    </div>
</template>
