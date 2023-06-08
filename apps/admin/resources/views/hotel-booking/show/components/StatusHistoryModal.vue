<script setup lang="ts">

import { computed, watch } from 'vue'

import { MaybeRef } from '@vueuse/core'
import { z } from 'zod'

import { useBookingStatusHistoryAPI } from '~api/booking/status'

import { formatDateTime } from '~lib/date'
import { requestInitialData } from '~lib/initial-data'

import BaseDialog from '~components/BaseDialog.vue'

const props = defineProps<{
  opened: MaybeRef<boolean>
}>()

defineEmits<{
  (event: 'close'): void
}>()

const { bookingID } = requestInitialData(
  'view-initial-data-hotel-booking',
  z.object({
    bookingID: z.number(),
  }),
)

const {
  data: statusHistoryEvents,
  execute: fetchStatusHistory,
  isFetching,
} = useBookingStatusHistoryAPI({ bookingID })

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
          <td>{{ statusEvent.event }}</td>
          <td>-</td>
          <td>-</td>
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
