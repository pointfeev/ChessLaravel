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
const pieces = ref(state['pieces']);
let moves = state['moves'];

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
    highlighted = container.value.querySelector('#squares :nth-child(' + position + ')')
    highlighted.classList.replace('square-even', 'square-even-selected');
    highlighted.classList.replace('square-odd', 'square-odd-selected');
}

let debounce = false;

function click(position) {
    if (debounce) {
        return;
    }
    let piece = pieces.value[position];
    if (selection == null) {
        if (piece != null) {
            select(position);
        }
        return;
    }
    if (selection === position) {
        select();
        return;
    }
    let selectedPiece = pieces.value[selection];
    if (!moves[selection].includes(position)) {
        if (piece != null && piece.color === selectedPiece.color) {
            select(position);
            return;
        }
        return;
    }
    debounce = true;
    axios.post(route('play.move'), {
        from: selection,
        to: position
    }).then(response => {
        let data = response.data;
        if (!data.success) {
            // TODO: error notification?
        }
        update(data.state);
    }).finally(() => {
        debounce = false;
    });
    pieces.value[position] = selectedPiece;
    pieces.value[selection] = null;
    select();
}

function update(state) {
    select();
    pieces.value = state['pieces'];
    moves = state['moves'];
}

defineExpose({update});
</script>

<template>
    <div ref='container' class='relative w-full h-full select-none'>
        <div v-if="boardSize !== '0rem'" id='board' class='absolute rounded shadow overflow-hidden'>
            <div id='squares' class='absolute w-full h-full grid grid-cols-8 grid-rows-8'>
                <div v-for='p in 64'
                     :class="p % 2 === Math.ceil(p / 8) % 2 ? 'square-even' : 'square-odd'"/>
            </div>
            <div v-if='pieces' id='pieces' class='absolute w-full h-full grid grid-cols-8 grid-rows-8'>
                <!--
                    TODO: convert iteration to (item, index) in items
                          and figure out how to position them manually
                          as to allow dragging?
                 -->
                <template v-for='p in 64' :key='p'>
                    <Piece v-if='pieces[p]' :color='pieces[p].color' :type='pieces[p].type'
                           @dragstart.prevent @click='click(p)'/>
                    <div v-else @click='click(p)'/>
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
