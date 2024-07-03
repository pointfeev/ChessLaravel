<script setup>
import {ref, onMounted, onUnmounted} from 'vue';
import Piece from '@/Components/Chess/Piece.vue';

const container = ref(null);

const boardSize = ref('150rem');

function onResize() {
    const div = container.value;
    const width = div.clientWidth;
    const height = div.clientHeight;
    const size = Math.min(width, height, 2400);
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
let turn = state['turn'];

function update(state) {
    pieces.value = state['pieces'];
    moves = state['moves'];
    turn = state['turn'];
    select();
}

const emit = defineEmits(['update']);
defineExpose({update});

let selection = null;
let highlighted = null;
const hinted = [];

function select(position) {
    selection = null;
    if (highlighted != null) {
        highlighted.classList.replace('square-even-selected', 'square-even');
        highlighted.classList.replace('square-odd-selected', 'square-odd');
        highlighted = null;
    }
    while (hinted.length > 0) {
        const hint = hinted.pop();
        hint.remove();
    }

    if (position == null) {
        return;
    }
    const validMoves = moves[position];
    if (validMoves == null || validMoves.length < 1) {
        return;
    }

    selection = position;

    highlighted = container.value.querySelector('#squares :nth-child(' + position + ')');
    highlighted.classList.replace('square-even', 'square-even-selected');
    highlighted.classList.replace('square-odd', 'square-odd-selected');

    for (const key in validMoves) {
        const movePosition = validMoves[key];
        const square = container.value.querySelector('#squares :nth-child(' + movePosition + ')');
        const piece = pieces.value[movePosition];

        const hint = document.createElement('div');
        hint.classList.add(piece != null ? 'capture-hint' : 'move-hint');
        square.appendChild(hint);
        hinted.push(hint);
    }
}

function click(position) {
    if (selection === position) {
        select();
        return;
    }

    if (selection == null || !moves[selection].includes(position)) {
        select(position);
        return;
    }

    move(selection, position);
}

let debounce = false;

function move(from, to) {
    if (debounce) {
        return;
    }
    debounce = true;

    pieces.value[to] = pieces.value[from];
    pieces.value[from] = null;
    turn++;

    state['pieces'] = pieces.value;
    state['turn'] = turn;
    emit('update', state);

    axios.post(route('play.move'), {
        from: from,
        to: to
    }).then(response => {
        const data = response.data;
        if (!data.success) {
            // TODO: error notification
        }
        emit('update', data.state);
    }).finally(() => {
        debounce = false;
    });
}
</script>

<template>
    <div ref='container' class='relative w-full h-full select-none'>
        <div v-if="boardSize !== '0rem'" id='board'
             class='absolute left-1/2 transform -translate-x-1/2 rounded shadow overflow-hidden'>
            <div id='squares' class='w-full h-full grid grid-cols-8 grid-rows-8'>
                <div v-for='p in 64' class='relative'
                     :class="p % 2 === Math.ceil(p / 8) % 2 ? 'square-even' : 'square-odd'"
                     @dragstart.prevent @click='click(p)'>
                    <Piece v-if='pieces[p]' :color='pieces[p].color' :type='pieces[p].type'/>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
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

.move-hint, .capture-hint {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: 50%;
}

.move-hint {
    background-color: rgba(0, 0, 0, 0.15);
    background-clip: content-box;
    padding: 34%;
}

.capture-hint {
    border: 3px solid rgba(0, 0, 0, 0.15);
}
</style>
