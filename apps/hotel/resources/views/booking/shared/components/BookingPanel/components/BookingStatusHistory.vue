<script setup lang="ts">

import { useToggle } from '@vueuse/core'

import StatusHistoryModal from '~resources/views/booking/shared/components/StatusHistoryModal.vue'
import { useBookingStatusHistoryStore } from '~resources/views/booking/shared/store/status-history'

const [isHistoryModalOpened, toggleHistoryModal] = useToggle<boolean>(false)

const statusHistoryStore = useBookingStatusHistoryStore()
const { fetchStatusHistory } = statusHistoryStore

</script>

<template>
  <StatusHistoryModal
    :opened="isHistoryModalOpened"
    :is-fetching="statusHistoryStore.isFetching"
    :status-history-events="statusHistoryStore.statusHistoryEvents"
    @close="toggleHistoryModal(false)"
    @refresh="fetchStatusHistory"
  />
  <a href="#" class="btn-log" @click.prevent="toggleHistoryModal()">История изменений</a>
</template>
