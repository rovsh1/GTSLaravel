<script setup lang="ts">

import { computed } from 'vue'

import { useDeleteWithConfirm } from 'gts-common/helpers/delete-dialog'
import { z } from 'zod'

import { useBookingStore } from '~resources/views/booking/shared/store/booking'

import { requestInitialData } from 'gts-common/helpers/initial-data'

const { editUrl, deleteUrl, timelineUrl } = requestInitialData(z.object({
  editUrl: z.string().nullable(),
  deleteUrl: z.string().nullable(),
  timelineUrl: z.string().optional(),
}))

const bookingStore = useBookingStore()
const canEdit = computed(() => Boolean(bookingStore?.availableActions?.isEditable))

const canCopy = computed(() => bookingStore.availableActions?.canCopy || false)

const handleCopyBooking = () => {
  bookingStore.copy()
}

const handleDelete = () => {
  if (!deleteUrl) {
    return
  }
  useDeleteWithConfirm(deleteUrl)
}
</script>

<template>
  <div class="dropdown menu-actions-wrapper">
    <div
      v-if="canEdit || canCopy || timelineUrl"
      id="menu-actions"
      class="btn btn-menu"
      href="#"
      role="button"
      data-bs-toggle="dropdown"
      aria-expanded="true"
    >
      <i class="icon">more_vert</i>
    </div>

    <ul class="dropdown-menu" aria-labelledby="menu-actions" data-popper-placement="bottom-start">
      <li v-if="timelineUrl">
        <a class="dropdown-item" :href="timelineUrl">
          <i class="icon">history</i>
          История брони
        </a>
      </li>
      <template v-if="canCopy">
        <a class="dropdown-item" href="#" @click.prevent="handleCopyBooking">
          <i class="icon">content_copy</i>
          Копировать
        </a>
      </template>
      <template v-if="canEdit">
        <li v-if="editUrl">
          <a class="dropdown-item" :href="editUrl">
            <i class="icon">edit</i>
            Редактировать
          </a>
        </li>
        <li v-if="deleteUrl">
          <a class="dropdown-item" href="#" @click.prevent="handleDelete">
            <i class="icon">delete</i>
            Удалить
          </a>
        </li>
      </template>
    </ul>
  </div>
</template>

<style scoped lang="scss">
  a {
    cursor: pointer;
  }
</style>
