<script setup lang="ts">
import { computed, unref } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BookingStatusesResponse } from '~api/booking/status'

import ButtonLoadingSpinner from '~components/ButtonLoadingSpinner.vue'

const props = defineProps<{
  modelValue: number
  statuses: MaybeRef<BookingStatusesResponse[]>
  availableStatuses: MaybeRef<BookingStatusesResponse[] | null>
  isLoading?: MaybeRef<boolean>
}>()

const emit = defineEmits<{
  (event: 'update:modelValue', value: number): void
  (event: 'change', value: number): void
}>()

const statuses = unref(props.statuses)

const label = computed<string | undefined>(() => {
  const status = statuses.find((statusData: BookingStatusesResponse) => statusData.id === props.modelValue)
  return status?.name
})

const statusClass = computed<string>(() => {
  const status = statuses.find((statusData: BookingStatusesResponse) => statusData.id === props.modelValue)
  if (status?.key) {
    return `BOOKING_STATUS_${status.key}`
  }
  return ''
})

const availableStatuses = computed<BookingStatusesResponse[] | null>(() => unref(props.availableStatuses))

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
          :class="`dropdown-item BOOKING_STATUS BOOKING_STATUS_${status.key}`"
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

.BOOKING_STATUS_CREATED {
  background-color: var(--bs-red);
  color: #fff;
  font-weight: bold;
}

.BOOKING_STATUS_CONFIRMED {
  background-color: #63C74DFF;
  color: #000;
  font-weight: bold;
}

.BOOKING_STATUS_WAITING_CONFIRMATION {
  background-color: var(--bs-yellow);
  color: #000;
  font-weight: bold;
}

.BOOKING_STATUS_INVOICED {
  background-color: var(--bs-info);
  color: #fff;
  font-weight: bold;
}

/* .BOOKING_STATUS_CREATED:before{background-color:var(--red);} */
.BOOKING_STATUS_PROCESSING::before {
  background-color: var(--bs-orange);
}

.BOOKING_STATUS_PARTIALLY_PAID::before,
.BOOKING_STATUS_PAID::before {
  background-color: var(--bs-green);
}

.BOOKING_STATUS_CANCELLED::before,
.BOOKING_STATUS_CANCELLED_NO_FEE::before,
.BOOKING_STATUS_CANCELLED_FEE::before,
.BOOKING_STATUS_REFUND_NO_FEE::before,
.BOOKING_STATUS_REFUND_FEE::before {
  background-color: var(--bs-dark);
}

/* .BOOKING_STATUS_CONFIRMED:before, */

/* .BOOKING_STATUS_INVOICED:before{background-color:var(--info);} */
.BOOKING_STATUS_WAITING_CANCELLATION::before,

/* .BOOKING_STATUS_WAITING_CONFIRMATION:before, */
.BOOKING_STATUS_WAITING_PROCESSING::before,
.BOOKING_STATUS_NOT_CONFIRMED::before {
  background-color: var(--litepicker-button-cancel-color)
}

</style>
