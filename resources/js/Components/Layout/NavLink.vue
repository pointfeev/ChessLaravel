<script setup>
import {Link} from '@inertiajs/vue3';
</script>

<template>
    <Link class='flex-initial bg-slate-300/10 hover:bg-slate-300/20 rounded-lg shadow py-1.5 px-3 font-semibold'
          v-bind='$attrs' v-on='$listeners'>
        <slot/>
    </Link>
</template>
