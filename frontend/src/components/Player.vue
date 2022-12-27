<script lang="ts">
    import { toRefs } from 'vue'

    export default {
        name: 'PlayerComponent',
        props: {
            is_revealed: {
                type: Number,
                required: true,
            },
            card: {
                type: String,
                default: null,
            },
            name: {
                type: String,
                required: true,
            },
        },
        setup(props: any) {
            const { is_revealed, card, name } = toRefs(props)
            return {
                is_revealed,
                card,
                name,
            }
        },
    }
</script>

<template>
    <section>
        <div class="w-[118px] h-[163px] mx-2 group perspective text-slate-700">
            <div
                class="relative preserve-3d w-full h-full duration-500 rounded-xl"
                :class="{ 'my-rotate-y-180': is_revealed, ' bg-darker-green': card != null, ' shadow-xl': card != null }"
            >
                <div class="absolute backface-hidden w-full h-full">
                    <div class="border-2 rounded-xl border-even-darker-green h-full flex justify-center items-center">
                        <img src="logo.png" width="100" />
                    </div>
                </div>
                <div class="absolute my-rotate-y-180 backface-hidden w-full h-full">
                    <div class="border-2 rounded-xl border-even-darker-green h-full flex flex-col">
                        <div class="flex justify-end p-1">
                            <img src="logo.png" width="25" />
                        </div>
                        <div class="mt-5">
                            <div class="w-full h-full flex justify-center items-center text-6xl">
                                <b>{{ card }}</b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center text-teal-900 mx-4 whitespace-nowrap overflow-hidden text-ellipsis" style="width:105px">
            <b>{{ name }}</b>
        </div>
    </section>
</template>
