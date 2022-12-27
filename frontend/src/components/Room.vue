<script setup lang="ts">
    import PlayerComponent from './Player.vue'
    import VotingCardComponent from './VotingCard.vue'
    import { useFetch } from '@vueuse/core'
    import { ref } from 'vue'

    const id = window.location.pathname.split('/').pop()
    const playerName = localStorage.getItem('playerName')

    const is_revealed = ref(false)
    const players = ref([])
    const roomName = ref('asd')
    const avaibleCards = ref([
        { value: 'XS', isSelected: false },
        { value: 'S', isSelected: false },
        { value: 'M', isSelected: false },
        { value: 'L', isSelected: false },
        { value: 'XL', isSelected: false },
        { value: 'XXL', isSelected: false },
    ])

    const toggle = () => {
        useFetch('api/toggleRoom/' + id).get()
    }

    var url = new URL(window.location.href)
    url.port = '6001'
    const ws = new WebSocket('ws://' + url.host + '/app/poker')
    ws.onopen = function () {
        //Subscribe to the channel
        ws.send(JSON.stringify({ event: 'pusher:subscribe', data: { channel: id } }))
    }
    ws.onmessage = function (msg) {
        const message = JSON.parse(msg.data)
        const channel = message.channel

        if (channel == id && message.event !== 'pusher_internal:subscription_succeeded') {
            const data = JSON.parse(message.data)
            if(is_revealed.value && !data.room.is_showing_cards) {
                avaibleCards.value.map((card) => (card.isSelected = false))
            }
            is_revealed.value = data.room.is_showing_cards

            if (!data.room.players.find((player) => player.id == localStorage.getItem('playerId'))) {
                localStorage.removeItem('roomId')
                window.location.replace('/')
            }
            players.value = data.room.players

            const player = players.value.find((player) => player.id == localStorage.getItem('playerId'))
            if ((localStorage.getItem('roomId') && player == undefined) || player.name !== localStorage.getItem('playerName')) {
                localStorage.clear()
                window.location.reload()
            }

        }
    }

    window.addEventListener('beforeunload', function (e) {
        e.preventDefault()
        var url = '/api/joinroom/'
        var data = JSON.stringify({ player: localStorage.getItem('playerId'), room: null })
        navigator.sendBeacon(url, data)
    })

    const poll = () => {
        const { data, onFetchResponse, onFetchError } = useFetch('api/room/' + id)
            .get()
            .json()
        onFetchResponse(() => {
            if (data.value.players.find((player) => player.id == localStorage.getItem('playerId')) == undefined) {
                const { data, onFetchResponse } = useFetch('api/joinroom').post({ player: localStorage.getItem('playerId'), room: id })
                localStorage.setItem('roomId', id)
            }
            players.value = data.value.players
            is_revealed.value = data.value.is_showing_cards
            roomName.value = data.value.name
        })
        onFetchError(() => {
            window.location.replace('/')
        })
    }

    const selectVotingCard = (cardValue: string) => {
        if (is_revealed.value) return
        avaibleCards.value.filter((card) => card.value == cardValue).map((card) => (card.isSelected = true))
        avaibleCards.value.filter((card) => card.value != cardValue).map((card) => (card.isSelected = false))
        useFetch('api/setPlayerCardValue').post({ player: localStorage.getItem('playerId'), card: cardValue })
    }

    const leaveRoom = () => {
        const { data, onFetchResponse } = useFetch('api/joinroom').post({ player: localStorage.getItem('playerId'), room: null })
        localStorage.removeItem('roomId')

        onFetchResponse(() => {
            window.location.replace('/')
        })
    }

    poll()
</script>

<template>
    <div class="h-full">
        <div class="flex justify-between">
            <div class="m-5 text-2xl text-slate-700 flex gap-2">
                <div>
                    <img src="https://cdn-icons-png.flaticon.com/512/5509/5509366.png" width="32" height="32" />
                </div>
                <div>
                    {{ playerName }}
                </div>
            </div>
            <div class="flex flex-col">
                <div class="m-5 text-2xl text-slate-700 flex flex-row-reverse gap-2 mr-12">
                    <div>
                        <img src="https://cdn-icons-png.flaticon.com/512/33/33308.png" width="32" height="32" />
                    </div>
                    <div>
                        {{ roomName }}
                    </div>
                </div>
                <div class="flex flex-row-reverse">
                    <button
                        @click="leaveRoom()"
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
                            z-50
                        "
                    >
                        Leave room
                    </button>
                </div>
            </div>
        </div>
        <div class="flex justify-center flex-col min-h-[calc(100vh_-_18rem)]">
            <div>
                <div class="flex justify-center -mt-16">
                    <player-component
                        v-for="player in players"
                        :is_revealed="is_revealed"
                        :card="player.card"
                        :name="player.name"
                        :isSelected="player.isSelected"
                    />
                </div>
            </div>
            <div class="flex justify-center mt-10">
                <button
                    :disabled="!players.filter((player) => player.card != null).length"
                    @click="toggle()"
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
                        mb-5
                    "
                    :class="{
                        'bg-light-grey hover:bg-slate-400 hover:text-slate-800 shadow-xl': players.filter((player) => player.card != null)
                            .length,
                        'bg-slate-200': !players.filter((player) => player.card != null).length,
                    }"
                >
                    {{ is_revealed ? 'New Round' : 'Reveal!' }}
                </button>
            </div>
        </div>
        <div class="flex justify-center">
            <voting-card-component
                class="mx-2"
                v-for="card in avaibleCards"
                :cardValue="card.value"
                :isSelected="card.isSelected"
                :isDisabled="is_revealed == true"
                @selectVotingCard="selectVotingCard"
            />
        </div>
    </div>
</template>
