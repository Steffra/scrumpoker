<script lang="ts" setup>
    import { ref } from 'vue'
    import { useFetch } from '@vueuse/core'

    const name = ref('')
    const playerName = localStorage.getItem('playerName')

    const roomid = ref()
    const createRoom = () => {
        if (name.value === '') {
            alert('Please enter a name')
            return
        }
        const { data, onFetchResponse } = useFetch('api/createroom/').post({ name: name.value }).json()
        onFetchResponse(() => {
            joinRoom(data.value.id)
        })
    }

    const joinRoom = (roomid) => {
        useFetch('api/joinroom').post({ player: localStorage.getItem('playerId'), room: roomid })
        localStorage.setItem('roomId', roomid!)
        window.location.replace('/' + roomid)
    }
</script>

<template>
    <div class="flex">
        <div class="m-5 text-2xl text-slate-700 flex gap-2">
            <div>
                <img src="https://cdn-icons-png.flaticon.com/512/5509/5509366.png" width="32" height="32" />
            </div>
            <div>
                {{ playerName }}
            </div>
        </div>
    </div>
    <div class="flex flex-col justify-center items-center min-h-[calc(100vh_-_18rem)]">
        <div>
            <input
                class="text-center border border-dark-grey rounded-lg py-3 text-2xl focus:outline-none w-60"
                type="text"
                v-model="name"
                placeholder="Game's name"
                v-on:keyup.enter="createRoom"
                autofocus
            />
        </div>
        <div class="text-center mt-5">
            <button
                @click="createRoom"
                class="
                    uppercase
                    tracking-wider
                    font-semibold
                    w-28
                    text-lg
                    rounded-lg
                    focus:outline-none
                    p-2
                    px-5
                    mt-3
                    bg-light-grey
                    hover:bg-slate-200
                "
            >
                Create
            </button>
        </div>
    </div>
</template>
