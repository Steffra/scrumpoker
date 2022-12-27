<script lang="ts" setup>
    import RoomComponent from '/@/components/Room.vue'
    import LobbyComponent from '/@/components/Lobby.vue'
    import JoinComponent from '/@/components/Join.vue'
    import CreateRoom from '/@/components/CreateRoom.vue'
    import { ref } from 'vue'

    const isNeedPlayerData = localStorage.getItem('playerId') == null
    const is_in_room = window.location.pathname.split('/').pop()
    const is_create_room = window.location.pathname.indexOf('createroom') != -1

</script>

<template>
    <div class="min-h-screen bg-gradient-to-t from-darker-green to-light-green w-full">
        <div v-if="isNeedPlayerData">
            <JoinComponent />
        </div>
        <div v-else>
            <create-room v-if="is_create_room" />
            <room-component v-else-if="is_in_room" />
            <lobby-component v-else />
        </div>
    </div>
</template>
