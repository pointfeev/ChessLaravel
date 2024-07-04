<script setup>
const {turn} = defineProps(['turn']);
</script>

<template>
    <div class='flex-initial bg-slate-300/10 rounded-lg shadow py-1.5 px-3'
         v-bind='$attrs' v-on='$listeners'>
        <span class='font-semibold'>Turn {{ turn }}: </span>
        <span>{{ turn % 2 === 0 ? 'White' : 'Black' }}</span>
    </div>
</template>
