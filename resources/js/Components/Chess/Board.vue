<script setup>
import {ref, onMounted, onUnmounted} from 'vue';
import Piece from '@/Components/Chess/Piece.vue';

const container = ref(null);
const boardSize = ref('150rem');

function onResize() {
    let div = container.value;
    let width = div.clientWidth;
    let height = div.clientHeight;
    let size = Math.min(width, height, 2400);
    boardSize.value = (size / 16).toString() + 'rem';
}

onMounted(() => {
    window.addEventListener('resize', onResize);
    onResize();
});
onUnmounted(() => {
    window.removeEventListener('resize', onResize);
});

const {state} = defineProps(['state']);
const pieces = ref(state.pieces);

let selection = null;
let highlighted = null;

function select(position) {
    if (highlighted != null) {
        highlighted.classList.replace('square-even-selected', 'square-even');
        highlighted.classList.replace('square-odd-selected', 'square-odd');
    }
    selection = position;
    if (position == null) {
        highlighted = null;
        return;
    }
    highlighted = container.value.querySelector('#squares div[data-position=\'' + position + '\']')
    highlighted.classList.replace('square-even', 'square-even-selected');
    highlighted.classList.replace('square-odd', 'square-odd-selected');
}

let debounce = false;

function click(position, isPiece) {
    if (debounce) {
        return;
    }
    if (selection == null) {
        if (isPiece) {
            select(position);
        }
        return;
    }
    if (selection === position) {
        select();
        return;
    }
    debounce = true;
    axios.post(route('play.move'), {
        from: selection,
        to: position
    }).then(response => {
        let data = response.data;
        if (!data.success) {
            return;
        }
        update(data.state);
    }).finally(() => {
        debounce = false;
    });
}

function update(state) {
    select();
    pieces.value = state.pieces;
}

defineExpose({update});
</script>

<template>
    <div ref='container' class='relative w-full h-full select-none'>
        <div id='board' class='absolute rounded-xl shadow overflow-hidden'>
            <div id='squares' class='absolute w-full h-full grid grid-cols-8 grid-rows-8'>
                <div v-for='n in 64' :data-position='n'
                     :class="n % 2 === (Math.ceil(n / 8) % 2 === 0 ? 1 : 0) ? 'square-odd' : 'square-even'"/>
            </div>
            <div v-if='pieces' id='pieces' class='absolute w-full h-full grid grid-cols-8 grid-rows-8'>
                <!--
                    TODO: convert iteration to (item, index) in items
                          and figure out how to position them manually?
                 -->
                <template v-for='n in 64' :key='n'>
                    <Piece v-if='pieces[n]' :data-position='n'
                           :color='pieces[n][0]' :data-color='pieces[n][0]'
                           :type='pieces[n][1]' :data-type='pieces[n][1]'
                           @dragstart.prevent @click='click(n, true)'/>
                    <div v-else :data-position='n'
                         @click='click(n, false)'/>
                </template>
            </div>
        </div>
    </div>
</template>

<style scoped>
#board {
    width: v-bind(boardSize);
    height: v-bind(boardSize);
}

.square-even {
    background-color: rgb(242 225 195);
}

.square-even-selected {
    background-color: rgb(249 240 123);
}

.square-odd {
    background-color: rgb(195 160 130);
}

.square-odd-selected {
    background-color: rgb(226 207 89);
}
</style>
