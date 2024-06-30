<script setup>
import {ref} from 'vue';
import Layout from '@/Layout/Layout.vue';
import NavLink from '@/Components/Layout/NavLink.vue';
import Board from '@/Components/Chess/Board.vue';

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
        <template v-slot:header>
            <NavLink :href="route('welcome')">Back</NavLink>
            <NavLink href='#' onclick='return false' @click='reset'>Reset</NavLink>
        </template>
        <Board ref='board' :state='state'/>
    </Layout>
</template>
