<script setup lang="ts">

import EditTableRowButton from '~resources/views/hotel/settings/components/EditTableRowButton.vue'

const props = defineProps<{
  orderBookings: any[]
  canEdit: boolean
}>()

defineEmits<{
  (event: 'edit', guest: any): void
  (event: 'delete', guest: any): void
}>()

</script>

<template>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>№</th>
        <th class="column-text">Название</th>
        <th class="column-text">Период</th>
        <th class="column-text">Стоимость</th>
        <th class="column-text">Статус</th>
        <th class="column-text">Отмена</th>
        <th v-if="canEdit" />
      </tr>
    </thead>
    <tbody>
      <template v-if="orderBookings.length > 0">
        <tr v-for="(booking) in orderBookings" :key="booking.id">
          <td />
          <td />
          <td />
          <td />
          <td />
          <td />
          <td v-if="canEdit" class="column-edit">
            <EditTableRowButton
              @edit="$emit('edit', booking)"
              @delete="$emit('delete', booking)"
            />
          </td>
        </tr>
      </template>
      <template v-else>
        <tr>
          <td colspan="7" class="text-center">Брони не добавлены</td>
        </tr>
      </template>
    </tbody>
  </table>
</template>
