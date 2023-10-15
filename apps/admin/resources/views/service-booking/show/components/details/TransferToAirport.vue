<script setup lang="ts">

import { computed } from 'vue'

import CarsTable from '~resources/views/booking/components/CarsTable.vue'
import InfoBlock from '~resources/views/booking/components/InfoBlock/InfoBlock.vue'
import InfoBlockTitle from '~resources/views/booking/components/InfoBlock/InfoBlockTitle.vue'
import { useBookingStore } from '~resources/views/service-booking/show/store/booking'

import { useAdminAPI } from '~api'

import EditableDateInput from '~components/Editable/EditableDateInput.vue'
import EditableTextarea from '~components/Editable/EditableTextarea.vue'
import EditableTextInput from '~components/Editable/EditableTextInput.vue'
import IconButton from '~components/IconButton.vue'

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
  <div class="d-flex flex-row gap-4">
    <InfoBlock>
      <template #header>
        <InfoBlockTitle title="Параметры размещения" />
      </template>
      <table class="table-params">
        <tbody>
          <tr>
            <th>Дата начала аренды</th>
            <td>
              <EditableDateInput :value="undefined" />
            </td>
          </tr>
          <tr>
            <th>Дата завершения аренды</th>
            <td>
              <EditableDateInput :value="undefined" />
            </td>
          </tr>
          <tr>
            <th>Время подачи авто</th>
            <td>
              <EditableTextInput :value="''" type="time" />
            </td>
          </tr>
          <tr>
            <th>Место подачи авто</th>
            <td>
              <EditableTextInput :value="''" type="text" />
            </td>
          </tr>
          <tr>
            <th>Табличка для встречи</th>
            <td>
              <EditableTextInput :value="''" type="text" />
            </td>
          </tr>
          <tr>
            <th>Примечание (запрос в отель, ваучер)</th>
            <td>
              <EditableTextarea :value="''" />
            </td>
          </tr>
        </tbody>
      </table>
    </InfoBlock>

    <InfoBlock>
      <template #header>
        <div class="d-flex gap-1">
          <InfoBlockTitle title="Список автомобилей" />
          <IconButton v-if="isEditableStatus" icon="add" @click="() => { }" />
        </div>
      </template>
      <CarsTable
        :can-edit="isEditableStatus"
        :car-ids="[]"
        :order-cars="[]"
        @edit="(car) => { }"
        @delete="(car) => { }"
      />
    </InfoBlock>
  </div>
</template>
