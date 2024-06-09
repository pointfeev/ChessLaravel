<script setup>
import {ref} from 'vue';
import Layout from '@/Layout/Layout.vue';
import {Link} from '@inertiajs/vue3';
import Board from '@/Components/Board.vue';

defineProps(['state']);
const board = ref(null);

function reset() {
    axios.post(route('play.reset')).then(response => {
        let data = response.data;
        if (!data.success) {
            return;
        }
        board.value.update(data.state);
    })
}
</script>

<template>
    <Layout title='Play'>
        <template v-slot:navigation>
            <Link :href="route('welcome')">Back</Link>
            <Link href='#' onclick='return false' @click='reset'>Reset</Link>
        </template>
        <Board ref='board' :state='state'/>
    </Layout>
</template>
