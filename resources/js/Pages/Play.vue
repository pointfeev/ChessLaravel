<script setup>
import {ref} from 'vue';
import Layout from '@/Layout/Layout.vue';
import NavLink from '@/Components/Layout/NavLink.vue';
import Board from '@/Components/Chess/Board.vue';
import TurnDisplay from '@/Components/Chess/TurnDisplay.vue';

const board = ref(null);

const {state} = defineProps(['state']);
const turn = ref(state['turn']);

let debounce = false;

function reset() {
    if (debounce) {
        return;
    }

    debounce = true;
    axios.post(route('play.reset')).then(response => {
        let data = response.data;
        if (!data.success) {
            // TODO: error notification
        }
        update(data.state);
        debounce = false;
    })
}

function update(state) {
    if (state == null) {
        return;
    }
    turn.value = state['turn'];
    board.value.update(state);
}
</script>

<template>
    <Layout title='Play'>
        <template v-slot:header>
            <NavLink :href="route('welcome')">Back</NavLink>
            <NavLink href='#' onclick='return false' @click='reset'>Reset</NavLink>

            <div class='flex-auto'></div>

            <TurnDisplay :turn='turn'/>
        </template>

        <Board ref='board' :state='state' @update='update'/>
    </Layout>
</template>
