<script setup lang="ts">

import { computed } from 'vue'

import { z } from 'zod'

import InfoBlock from '~resources/views/booking/shared/components/InfoBlock/InfoBlock.vue'
import InfoBlockTitle from '~resources/views/booking/shared/components/InfoBlock/InfoBlockTitle.vue'
import BookingsTable from '~resources/views/booking-order/show/components/BookingsTable.vue'
import { useOrderStore } from '~resources/views/booking-order/show/store/order'

import { requestInitialData } from '~lib/initial-data'

import ActionsMenu, { Action } from '~components/ActionsMenu.vue'

const { serviceBookingCreate, hotelBookingCreate, clientID } = requestInitialData(z.object({
  serviceBookingCreate: z.string(),
  hotelBookingCreate: z.string(),
  clientID: z.number(),
}))

const orderStore = useOrderStore()

const isEditableStatus = computed<boolean>(() => orderStore.availableActions?.isEditable || false)

const orderBookings = computed(() => orderStore.bookings)

const actionsMenuSettings: Action[] = [{
  title: 'Создать отельную бронь',
  callback: () => { location.href = `${hotelBookingCreate}?client_id=${clientID}&order_id=${orderStore.order?.id}` },
},
{
  title: 'Создать бронь услуги',
  callback: () => { location.href = `${serviceBookingCreate}?client_id=${clientID}&order_id=${orderStore.order?.id}` },
}]

</script>

<template>
  <div class="mt-3" />
  <div class="d-flex flex-row gap-4">
    <InfoBlock>
      <template #header>
        <div class="d-flex gap-1 align-items-center mb-1">
          <InfoBlockTitle title="Список броней" />
          <ActionsMenu
            :can-edit="isEditableStatus"
            dropdown-button-icon="add"
            :actions="actionsMenuSettings"
          />
        </div>
      </template>

      <BookingsTable
        :can-edit="isEditableStatus"
        :order-bookings="orderBookings || []"
        @edit="(booking) => {}"
        @delete="(booking) => {}"
      />
    </InfoBlock>
  </div>
</template>

<style lang="scss" scoped>
.pt-card-title {
  margin-top: 0.85rem;
  padding-top: 0;
}
</style>
