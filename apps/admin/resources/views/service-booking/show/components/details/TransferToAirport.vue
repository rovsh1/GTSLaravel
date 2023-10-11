<script setup lang="ts">

import { computed } from 'vue'

import InfoBlock from '~resources/views/booking/components/InfoBlock/InfoBlock.vue'
import InfoBlockTitle from '~resources/views/booking/components/InfoBlock/InfoBlockTitle.vue'
import { useBookingStore } from '~resources/views/service-booking/show/store/booking'

import { useAdminAPI } from '~api'

import BootstrapButton from '~components/Bootstrap/BootstrapButton/BootstrapButton.vue'

const bookingStore = useBookingStore()

const isEditableStatus = computed<boolean>(() => bookingStore.availableActions?.isEditable || false)

const handleCreateDetails = () => {
  useAdminAPI(
    {},
    () => `/service-booking/${bookingStore.booking?.id}/details/${bookingStore.booking?.serviceType.id}/create`,
  ).post({
    test: 123,
  }).execute()
}
</script>

<template>
  <div class="flex flex-row gap-4">
    <InfoBlock>
      <template #header>
        <InfoBlockTitle title="Параметры размещения" />
      </template>
      <table class="table-params">
        <tbody>
          <tr>
            <th>Дата начала аренды</th>
            <td>-</td>
          </tr>
          <tr>
            <th>Дата завершения аренды</th>
            <td>-</td>
          </tr>
          <tr>
            <th>Время подачи авто</th>
            <td>-</td>
          </tr>
          <tr>
            <th>Место подачи авто</th>
            <td>-</td>
          </tr>
          <tr>
            <th>Табличка для встречи</th>
            <td>-</td>
          </tr>
          <tr>
            <th>Примечание (запрос в отель, ваучер)</th>
            <td>-</td>
          </tr>
        </tbody>
      </table>

      <div class="mt-2">
        <BootstrapButton v-if="isEditableStatus" label="Изменить" @click="handleCreateDetails" />
      </div>
    </InfoBlock>

    <InfoBlock class="mt-3">
      <template #header>
        <InfoBlockTitle title="Список автомобилей" />
      </template>
      <table class="table">
        <thead>
          <tr>
            <th class="text-nowrap">№</th>
            <th class="text-nowrap">Модель</th>
            <th class="text-nowrap">Кол-во авто</th>
            <th class="text-nowrap">Кол-во пассажиров</th>
            <th class="text-nowrap">Кол-во багажа</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>Мерседес Бенц Спринтер</td>
            <td>1</td>
            <td>15</td>
            <td>Без багажа</td>
          </tr>
        </tbody>
      </table>
    </InfoBlock>
  </div>
</template>
