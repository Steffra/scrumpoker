<script lang="ts">
    import { ref } from 'vue'
    import { useFetch } from '@vueuse/core'

    export default {
        name: 'JoinComponent',
        setup(props: any) {
            const name = ref('')
            const savePlayer = () => {
                if (name.value === '') {
                    alert('Please enter your name')
                    return
                }
                const { data, onFetchResponse } = useFetch('api/createplayer/' + name.value)
                    .get()
                    .json()
                onFetchResponse(() => {
                    joinPlayer(data)
                })
            }

            const joinPlayer = (data: any) => {
                const joiningRoomId = window.location.pathname.split('/').pop()
                console.log(joiningRoomId)
                console.log(data.value.room_id)
                console.log(joiningRoomId)
                if (joiningRoomId != '' && joiningRoomId != 'createroom') {
                    useFetch('api/joinroom').post({ player: data.value.id, room: joiningRoomId })
                    localStorage.setItem('roomId', joiningRoomId!)
                }
                localStorage.setItem('playerName', name.value)
                localStorage.setItem('playerId', data.value.id)
                location.reload()
            }

            return {
                name,
                savePlayer,
            }
        },
    }
</script>

<template>
    <div class="flex flex-col justify-center items-center min-h-screen">
        <div>
            <input
                class="text-center border border-dark-grey rounded-lg py-3 text-2xl focus:outline-none w-60"
                type="text"
                v-model="name"
                placeholder="Enter your name!"
                v-on:keyup.enter="savePlayer"
            />
        </div>
        <div class="text-center mt-5">
            <button
                @click="savePlayer()"
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
                Join
            </button>
        </div>
    </div>
</template>
