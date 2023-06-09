<script setup lang="ts">
import { computed, unref } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BookingStatusResponse } from '~api/booking/status'

import ButtonLoadingSpinner from '~components/ButtonLoadingSpinner.vue'

const props = defineProps<{
  modelValue: number
  statuses: MaybeRef<BookingStatusResponse[]>
  availableStatuses: MaybeRef<BookingStatusResponse[] | null>
  isLoading?: MaybeRef<boolean>
}>()

const emit = defineEmits<{
  (event: 'update:modelValue', value: number): void
  (event: 'change', value: number): void
}>()

const statuses = unref(props.statuses)

const label = computed<string | undefined>(() => {
  const status = statuses.find((statusData: BookingStatusResponse) => statusData.id === props.modelValue)
  return status?.name
})

const statusClass = computed<string>(() => {
  const status = statuses.find((statusData: BookingStatusResponse) => statusData.id === props.modelValue)
  return status?.color ? `text-bg-${status.color}` : 'text-bg-secondary'
})

const availableStatuses = computed<BookingStatusResponse[] | null>(() => unref(props.availableStatuses))

const isLoading = computed<boolean>(() => Boolean(unref(props.isLoading)))

const handleChangeStatus = (value: number): void => {
  setTimeout(() => {
    emit('update:modelValue', value)
    emit('change', value)
  })
}

</script>

<template>
  <div class="dropdown">
    <button
      :class="`btn btn-secondary dropdown-toggle w-25 align-left border-0 ${statusClass}`"
      type="button"
      :disabled="availableStatuses?.length === 0 || isLoading"
      data-bs-toggle="dropdown"
    >
      {{ label }}
      <ButtonLoadingSpinner :show="isLoading" />
    </button>
    <ul class="dropdown-menu">
      <li v-for="status in availableStatuses" :key="status.id">
        <a
          class="dropdown-item BOOKING_STATUS"
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
    min-width: 12.5rem;
    font-weight: bold;
    text-align: left;

    &:disabled {
      cursor: not-allowed;
    }
  }
}

.BOOKING_STATUS {
  position: relative;
  display: inline-block;
  padding-left: 1.75rem;
}

.BOOKING_STATUS::before {
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
}

</style>
