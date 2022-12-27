<script lang="ts">
    import { toRefs } from 'vue'

    export default {
        name: 'VotingCardComponent',
        props: {
            cardValue: {
                type: String,
                required: true,
            },
            isSelected: {
                type: Boolean,
                required: true,
            },
            isDisabled: {
                type: Boolean,
                required: true,
            },
        },
        setup(props: any, { emit }) {
            const { cardValue, isSelected, isDisabled } = toRefs(props)
            const select = () => {
                emit('selectVotingCard', cardValue.value)
            }

            return {
                cardValue,
                isSelected,
                isDisabled,
                select,
            }
        },
    }
</script>

<template>
    <div
        class="flex justify-center items-center cursor-pointer w-[59px] h-[81px] text-slate-700 bg-darker-green rounded-xl text-2xl"
        :class="{
            'border-teal-900 border-2 bg-even-darker-green': isSelected,
            'cursor-default border text-slate-500 border-slate-600': isDisabled,
            'hover:border-3 hover:border-teal-800 hover:shadow-xl  border-2 border-teal-700': !isDisabled,
        }"
        @click="select()"
    >
        <div>
            <b>{{ cardValue }}</b>
        </div>
    </div>
</template>
