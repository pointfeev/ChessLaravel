<script setup>
function getPieceImage(color, type) {
    return new URL(`../../../images/${color}${type}.webp`, import.meta.url).href
}

defineProps(['color', 'type']);
</script>

<template>
    <img class='w-full h-full' :src='getPieceImage(color, type)' :alt="color + type"
         v-bind='$attrs' v-on='$listeners'/>
</template>
