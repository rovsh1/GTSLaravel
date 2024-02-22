<script setup lang="ts">
import { computed, unref } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { BookingStatusResponse } from '~api/booking/models'
import { StatusSettings } from '~api/booking/status'

import ButtonLoadingSpinner from '~components/ButtonLoadingSpinner.vue'

const props = defineProps<{
  modelValue: BookingStatusResponse | StatusSettings
  statuses: MaybeRef<StatusSettings[]>
  availableStatuses: MaybeRef<StatusSettings[] | null>
  isLoading?: MaybeRef<boolean>
}>()

const emit = defineEmits<{
  (event: 'update:modelValue', value: BookingStatusResponse): void
  (event: 'change', value: number): void
}>()

const statuses = Object.values(unref(props.statuses))

const label = computed<string | undefined>(() => props.modelValue.name)

const statusClass = computed<string>(() => (props.modelValue.color ? `text-bg-${props.modelValue.color}` : 'text-bg-secondary'))

const availableStatuses = computed<StatusSettings[] | null>(() => unref(props.availableStatuses))

const existStatuses = computed<string>(() => (availableStatuses.value && availableStatuses.value?.length > 0 ? 'dropdown-toggle' : ''))

const isLoading = computed<boolean>(() => Boolean(unref(props.isLoading)))

const handleChangeStatus = (value: number): void => {
  const newStatus = statuses.find((status) => status.id === value)
  setTimeout(() => {
    emit('update:modelValue', newStatus as BookingStatusResponse)
    emit('change', value)
  })
}

</script>

<template>
  <div class="dropdown">
    <button
      :class="`btn btn-secondary ${existStatuses} w-25 align-left border-0 ${statusClass}`"
      type="button"
      :disabled="availableStatuses?.length === 0 || isLoading"
      data-bs-toggle="dropdown"
    >
      {{ label }}
      <ButtonLoadingSpinner :show="isLoading" />
    </button>
    <ul class="dropdown-menu w-100">
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
  .dropdown-toggle::after {
    position: absolute;
    top: 50%;
    right: 0.5em;
    border-top: 0.33em solid;
    border-right: 0.33em solid transparent;
    border-left: 0.33em solid transparent;
    transform: translateY(-50%);
  }

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
