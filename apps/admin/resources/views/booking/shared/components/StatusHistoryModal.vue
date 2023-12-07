<script setup lang="ts">

import { computed, watch } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BookingStatusHistoryResponse } from '~api/booking/status'

import { formatDateTime } from '~lib/date'

import BaseDialog from '~components/BaseDialog.vue'

const props = defineProps<{
  opened: MaybeRef<boolean>
  isFetching: boolean
  statusHistoryEvents: BookingStatusHistoryResponse[] | null
}>()

const emit = defineEmits<{
  (event: 'close'): void
  (event: 'refresh'): void
}>()

const isFetching = computed<boolean>(() => props.isFetching)

const statusHistoryEvents = computed<BookingStatusHistoryResponse[] | null>(() => props.statusHistoryEvents)

const isOpened = computed(() => props.opened)
watch(isOpened, (opened) => {
  if (opened) {
    emit('refresh')
  }
})

</script>

<template>
  <BaseDialog
    class="statusHistoryDialog"
    :auto-width="true"
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
            <span :class="`badge rounded-pill text-bg-${statusEvent.color} px-2`" class="event-badge">
              {{ statusEvent.event }}
            </span>
          </td>
          <td>{{ statusEvent.source }}</td>
          <td>{{ statusEvent.administratorName }}</td>
          <td>{{ formatDateTime(statusEvent.createdAt) }}</td>
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
  min-width: 50rem;
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

.event-badge {
  white-space: nowrap;
}
</style>
