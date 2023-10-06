<script setup lang="ts">

import { computed, onMounted, ref } from 'vue'

import { useToggle } from '@vueuse/core'
import { z } from 'zod'

import InfoBlock from '~resources/views/booking/components/InfoBlock/InfoBlock.vue'
import InfoBlockTitle from '~resources/views/booking/components/InfoBlock/InfoBlockTitle.vue'
import { TransferFormData } from '~resources/views/booking/lib/data-types'
import { useOrderStore } from '~resources/views/booking/store/order'
import TransferModal from '~resources/views/transfer-booking/show/components/TransferModal.vue'
import { useBookingStore } from '~resources/views/transfer-booking/show/store/booking'

import { Guest } from '~api/booking/order/guest'
import { useCountrySearchAPI } from '~api/country'

import { requestInitialData } from '~lib/initial-data'

import BootstrapButton from '~components/Bootstrap/BootstrapButton/BootstrapButton.vue'
import Card from '~components/Bootstrap/BootstrapCard/BootstrapCard.vue'
import CardTitle from '~components/Bootstrap/BootstrapCard/components/BootstrapCardTitle.vue'

const [isShowTransferModal, toggleTransferModal] = useToggle()

const { bookingID } = requestInitialData('view-initial-data-transfer-booking', z.object({
  bookingID: z.number(),
}))

const bookingStore = useBookingStore()
const orderStore = useOrderStore()
const orderId = computed(() => orderStore.order.id)
const orderGuests = computed<Guest[]>(() => orderStore.guests || [])
const booking = computed(() => bookingStore.booking)

const isEditableStatus = computed<boolean>(() => bookingStore.availableActions?.isEditable || false)

const { data: countries, execute: fetchCountries } = useCountrySearchAPI()

const transferForm = ref<Partial<TransferFormData>>({})

onMounted(() => {
  fetchCountries()
})

const onCloseModal = () => {
  transferForm.value = {}
  toggleTransferModal(false)
}

const onRoomModalSubmit = async () => {
  toggleTransferModal(false)
  // await fetchBooking()
}

</script>

<template>
  <TransferModal
    :opened="isShowTransferModal"
    :form-data="transferForm"
    @close="onCloseModal"
    @submit="onRoomModalSubmit"
  />
  <Card>
    <CardTitle class="mr-4" title="Информация о брони" />
    <div class="d-flex flex-row gap-4">
      <InfoBlock>
        <table class="table-params mb-3 mt-2">
          <tbody>
            <tr>
              <th>Номер поезда</th>
              <td>1234</td>
            </tr>
            <tr>
              <th>Дата прибытия</th>
              <td>2023-10-06</td>
            </tr>
            <tr>
              <th>Время прибытия</th>
              <td>12:00</td>
            </tr>
            <tr>
              <th>Город прибытия</th>
              <td>Ташкент</td>
            </tr>
            <tr>
              <th>Табличка для встречи</th>
              <td>Табличка</td>
            </tr>
          </tbody>
        </table>
        <InfoBlockTitle title="Список автомобилей" />
        <div class="mt-2">
          <table class="table">
            <thead>
              <tr>
                <th>№</th>
                <th class="text-nowrap">Модель</th>
                <th class="text-nowrap">Кол-во авто</th>
                <th class="text-nowrap">Кол-во пассажиров</th>
                <th class="text-nowrap">Кол-во багажа</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td class="text-nowrap">
                  Шевроле Кобальт
                </td>
                <td class="text-nowrap">1</td>
                <td class="text-nowrap">2</td>
                <td class="text-nowrap">1</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="d-flex flex-row justify-content-end w-100 mt-2">
          <BootstrapButton v-if="isEditableStatus" label="Изменить" @click="toggleTransferModal(true)" />
        </div>
      </InfoBlock>
    </div>
  </Card>
</template>

<style lang="scss" scoped>
.total-sum {
  margin-bottom: 0.5rem;
}
</style>
