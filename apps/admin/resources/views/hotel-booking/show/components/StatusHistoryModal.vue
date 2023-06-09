<script setup lang="ts">

import { computed, watch } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { useBookingStatusHistoryStore } from '~resources/views/hotel-booking/show/store/status-history'

import { BookingStatusHistoryResponse } from '~api/booking/status'

import { formatDateTime } from '~lib/date'

import BaseDialog from '~components/BaseDialog.vue'

const props = defineProps<{
  opened: MaybeRef<boolean>
}>()

defineEmits<{
  (event: 'close'): void
}>()

const statusHistoryStore = useBookingStatusHistoryStore()
const { fetchStatusHistory } = statusHistoryStore
const isFetching = computed<boolean>(() => statusHistoryStore.isFetching)
const statusHistoryEvents = computed<BookingStatusHistoryResponse[] | null>(() => statusHistoryStore.statusHistoryEvents)

const isOpened = computed(() => props.opened)
watch(isOpened, (opened) => {
  if (opened) {
    fetchStatusHistory()
  }
})

</script>

<template>
  <BaseDialog
    class="statusHistoryDialog"
    :opened="isOpened as boolean"
    :loading="isFetching"
    @close="$emit('close')"
  >
    <template #title>История статусов</template>
    <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">Событие</th>
          <th scope="col">Источник</th>
          <th scope="col">Пользователь</th>
          <th scope="col">Дата</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(statusEvent, idx) in statusHistoryEvents" :key="idx">
          <td>
            <span :class="`badge rounded-pill text-bg-${statusEvent.color} px-2`">
              {{ statusEvent.event }}
            </span>
          </td>
          <td>{{ statusEvent.source }}</td>
          <td>{{ statusEvent.administratorName }}</td>
          <td>{{ formatDateTime(statusEvent.dateCreate) }}</td>
        </tr>
        <tr v-if="!isFetching && statusHistoryEvents?.length === 0">
          <td colspan="4" class="grid-empty-text">Записи отсутствуют</td>
        </tr>
      </tbody>
    </table>
  </BaseDialog>
</template>

<style>
:root .statusHistoryDialog {
  --dialog-width: 50rem;
}
</style>

<style scoped lang="scss">
.event {
  &::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 1rem;
    display: inline-block;
    vertical-align: middle;
    width: 6px;
    height: 6px;
    margin-top: -3px;
    margin-right: 5px;
    border-radius: 50%;
    background-color: black;
  }
}
</style>
