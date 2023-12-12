<script setup lang="ts">

import { computed } from 'vue'

import { z } from 'zod'

import { useOrderStore } from '~resources/views/booking-order/show/store/order'

import { useDeleteWithConfirm } from '~lib/delete-dialog'
import { requestInitialData } from '~lib/initial-data'

const { editUrl, deleteUrl } = requestInitialData(z.object({
  editUrl: z.string().optional(),
  deleteUrl: z.string().optional(),
}))

const orderStore = useOrderStore()
const canEdit = computed(() => Boolean(orderStore?.availableActions?.isEditable))

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
      v-if="canEdit"
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
    </ul>
  </div>
</template>
