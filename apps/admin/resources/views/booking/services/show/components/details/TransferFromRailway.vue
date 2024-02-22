<script setup lang="ts">

import { computed } from 'vue'

import BookingCars from '~resources/views/booking/services/show/components/BookingCars.vue'
import { BookingTransferFromRailwayDetails } from '~resources/views/booking/services/show/components/details/lib/types'
import InfoBlock from '~resources/views/booking/shared/components/InfoBlock/InfoBlock.vue'
import { useBookingStore } from '~resources/views/booking/shared/store/booking'

import EditableDateInput from '~components/Editable/EditableDateInput.vue'
import EditableTextInput from '~components/Editable/EditableTextInput.vue'
import EditableTimeInput from '~components/Editable/EditableTimeInput.vue'

const bookingStore = useBookingStore()

const isEditableStatus = computed<boolean>(() => bookingStore.availableActions?.isEditable || false)

const bookingDetails = computed<BookingTransferFromRailwayDetails | null>(() => bookingStore.booking?.details || null)

const handleChangeDetails = async (field: string, value: any) => {
  await bookingStore.updateDetails(field, value)
}

</script>

<template>
  <div class="d-flex flex-row gap-4">
    <InfoBlock>
      <table class="table-params">
        <tbody>
          <tr>
            <th>Город</th>
            <td>
              {{ bookingDetails?.city.name }}
            </td>
          </tr>
          <tr>
            <th>Станция прибытия</th>
            <td>
              <EditableTextInput
                :value="bookingDetails?.railwayInfo?.name"
                :can-edit="false"
                type="text"
              />
            </td>
          </tr>
          <tr>
            <th>Номер поезда</th>
            <td>
              <EditableTextInput
                :value="bookingDetails?.trainNumber"
                :can-edit="isEditableStatus"
                type="text"
                @change="value => handleChangeDetails('trainNumber', value)"
              />
            </td>
          </tr>
          <tr>
            <th>Дата прибытия</th>
            <td>
              <EditableDateInput
                :value="bookingDetails?.arrivalDate"
                :can-edit="isEditableStatus"
                @change="value => handleChangeDetails('arrivalDate', value)"
              />
            </td>
          </tr>
          <tr>
            <th>Время прибытия</th>
            <td>
              <EditableTimeInput
                :value="bookingDetails?.arrivalDate"
                :can-edit="isEditableStatus && !!bookingDetails?.arrivalDate"
                type="time"
                @change="value => handleChangeDetails('arrivalDate', value)"
              />
            </td>
          </tr>
          <tr>
            <th>Табличка для встречи</th>
            <td>
              <EditableTextInput
                :value="bookingDetails?.meetingTablet"
                :can-edit="isEditableStatus"
                type="text"
                @change="value => handleChangeDetails('meetingTablet', value)"
              />
            </td>
          </tr>
        </tbody>
      </table>
    </InfoBlock>
  </div>

  <div class="mt-3" />
  <BookingCars />
</template>
