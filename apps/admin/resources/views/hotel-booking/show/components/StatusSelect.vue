<script setup lang="ts">
import { computed, unref } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BookingStatusesResponse, useBookingStatusesAPI } from '~api/booking/status'

const props = defineProps<{
  modelValue: number
  availableStatuses: MaybeRef<BookingStatusesResponse[] | null>
}>()

const emit = defineEmits<{
  (event: 'update:modelValue', value: number): void
  (event: 'change', value: number): void
}>()

const { data: statuses, execute: fetchStatuses, isFetching } = useBookingStatusesAPI()
fetchStatuses()

const label = computed<string | undefined>(() => {
  const status = statuses.value?.find((statusData: BookingStatusesResponse) => statusData.id === props.modelValue)
  return status?.name
})

const availableStatuses = computed<BookingStatusesResponse[] | null>(() => unref(props.availableStatuses))

const handleChangeStatus = (value: number): void => {
  emit('update:modelValue', value)
  emit('change', value)
}

</script>

<template>
  <div class="dropdown">
    <button
      class="btn btn-secondary dropdown-toggle w-25 align-left"
      type="button"
      :disabled="isFetching"
      data-bs-toggle="dropdown"
      aria-expanded="false"
    >
      {{ label }}
    </button>
    <ul class="dropdown-menu">
      <li v-for="status in availableStatuses" :key="status.id">
        <a
          class="dropdown-item"
          href="#"
          @click.prevent="handleChangeStatus(status.id)"
        >
          {{ status.name }}
        </a>
      </li>
    </ul>
  </div>
</template>

<style scoped lang="scss">
.dropdown {
  button {
    min-width: 9rem;
    font-weight: bold;
    text-align: left;

    &:disabled {
      cursor: not-allowed;
    }
  }
}
</style>
