<script setup lang="ts">

import { computed } from 'vue'

import BookingCars from '~resources/views/booking/services/show/components/BookingCars.vue'
import { BookingIntercityTransferDetails } from '~resources/views/booking/services/show/components/details/lib/types'
import InfoBlock from '~resources/views/booking/shared/components/InfoBlock/InfoBlock.vue'
import { useBookingStore } from '~resources/views/booking/shared/store/booking'

import EditableDateInput from '~components/Editable/EditableDateInput.vue'
import EditableTimeInput from '~components/Editable/EditableTimeInput.vue'

const bookingStore = useBookingStore()

const isEditableStatus = computed<boolean>(() => bookingStore.availableActions?.isEditable || false)

const bookingDetails = computed<BookingIntercityTransferDetails | null>(() => bookingStore.booking?.details || null)

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
            <th>Маршрут</th>
            <td>
              {{ bookingDetails?.fromCity.name }} - {{ bookingDetails?.toCity.name }}
            </td>
          </tr>
          <tr>
            <th>Дата</th>
            <td>
              <EditableDateInput
                :value="bookingDetails?.departureDate"
                :can-edit="isEditableStatus"
                @change="value => handleChangeDetails('departureDate', value)"
              />
            </td>
          </tr>
          <tr>
            <th>Время</th>
            <td>
              <EditableTimeInput
                :value="bookingDetails?.departureDate"
                :can-edit="isEditableStatus && !!bookingDetails?.departureDate"
                type="time"
                @change="value => handleChangeDetails('departureDate', value)"
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
