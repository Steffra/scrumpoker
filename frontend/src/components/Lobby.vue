<script setup lang="ts">
    import { ref } from 'vue'
    import { useFetch } from '@vueuse/core'

    const playerName = localStorage.getItem('playerName')
    const rooms = ref([])
    const { data, statusCode, onFetchResponse, onFetchError } = useFetch('api/rooms')
        .post({ playerId: localStorage.getItem('playerId'), playerName: localStorage.getItem('playerName') })
        .json()
    onFetchResponse(() => {
        rooms.value = data.value
    })
    onFetchError(() => {
        if(statusCode.value == 401) {
            localStorage.clear()
            window.location.reload()
        }
    })

    const joinRoom = (roomId: string) => {
        const currentPlayerId = localStorage.getItem('playerId')
        localStorage.setItem('roomId', roomId)
        const { data, onFetchResponse } = useFetch('api/joinroom').post({ player: currentPlayerId, room: roomId })
        onFetchResponse(() => {
            window.location.replace('/' + roomId)
        })
    }

    const createRoom = () => {
        window.location.replace('/createroom')
    }

    const signout = () => {
        localStorage.clear()
        window.location.reload()
    }

    var url = new URL(window.location.href)
    url.port = '6001'
    const ws = new WebSocket('ws://' + url.host + '/app/poker')
    ws.onopen = function () {
        //Subscribe to the channel
        ws.send(JSON.stringify({ event: 'pusher:subscribe', data: { channel: 'lobby' } }))
    }
    ws.onmessage = function (msg) {
        const message = JSON.parse(msg.data)
        const channel = message.channel
        if (message.event !== 'pusher_internal:subscription_succeeded') {
            const data = JSON.parse(message.data)
            console.log(data.rooms)
            rooms.value = data.rooms
        }
    }
</script>

<template>
    <div class="flex justify-between">
        <div class="m-5 text-2xl text-slate-700 flex gap-2">
            <div>
                <img src="https://cdn-icons-png.flaticon.com/512/5509/5509366.png" width="32" height="32" />
            </div>
            <div>
                {{ playerName }}
            </div>
        </div>
        <button
            @click="signout"
            class="
                m-5
                uppercase
                tracking-wider
                font-semibold
                w-48
                text-lg text-slate-700
                rounded-lg
                focus:outline-none
                p-2
                px-5
                bg-light-grey
                hover:bg-slate-400 hover:text-slate-800
                mb-5
            "
        >
            Sign out
        </button>
    </div>
    <div class="w-full h-full flex flex-col space-between">
        <div class="flex flex-col items-center justify-center min-h-[calc(100vh_-_10rem)]">
            <div
                v-for="room in rooms"
                @click="joinRoom(room.id)"
                class="
                    flex
                    items-center
                    w-full
                    sm:w-224
                    justify-between
                    mb-5
                    bg-darker-green
                    p-3
                    rounded-xl
                    cursor-pointer
                    hover:bg-even-darker-green
                    shadow-xl
                    hover:shadow-2xl
                "
            >
                <div>
                    <div class="font-medium text-xl mb-2 text-slate-700">{{ room.name }}</div>
                    <div class="flex" v-if="room.players.length">
                        <span class="mx-2 text-slate-200" v-for="player in room.players">
                            {{ player.name }}
                        </span>
                    </div>
                    <div v-else>
                        <span class="mx-2 text-sm text-slate-200"> --- This room is empty --- </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full flex flex-row-reverse">
            <button
                @click="createRoom()"
                class="
                    m-10
                    uppercase
                    tracking-wider
                    font-semibold
                    w-48
                    text-lg text-slate-700
                    rounded-lg
                    focus:outline-none
                    p-2
                    px-5
                    mt-3
                    bg-light-grey
                    hover:bg-slate-400 hover:text-slate-800
                    mb-5
                "
            >
                Create room
            </button>
        </div>
    </div>
</template>
