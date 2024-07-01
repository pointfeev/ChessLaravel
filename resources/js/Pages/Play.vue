<script setup>
import {ref} from 'vue';
import Layout from '@/Layout/Layout.vue';
import NavLink from '@/Components/Layout/NavLink.vue';
import Board from '@/Components/Chess/Board.vue';

defineProps(['state']);
const board = ref(null);

let debounce = false;
function reset() {
    debounce = true;
    axios.post(route('play.reset')).then(response => {
        let data = response.data;
        if (!data.success) {
            // TODO: error notification
        }
        board.value.update(data.state);
        debounce = false;
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
