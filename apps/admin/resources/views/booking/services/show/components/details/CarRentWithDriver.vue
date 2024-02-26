<script setup lang="ts">

import { computed } from 'vue'

import { formatDateTimeToAPIDateTime, parseAPIDateAndSetDefaultTime } from 'gts-common/helpers/date'

import BookingCars from '~resources/views/booking/services/show/components/BookingCars.vue'
import { BookingCarRentWithDriverDetails } from '~resources/views/booking/services/show/components/details/lib/types'
import InfoBlock from '~resources/views/booking/shared/components/InfoBlock/InfoBlock.vue'
import { useBookingStore } from '~resources/views/booking/shared/store/booking'

import EditableDateRangeInput from '~components/Editable/EditableDateRangeInput.vue'
import EditableTextInput from '~components/Editable/EditableTextInput.vue'
import EditableTimeInput from '~components/Editable/EditableTimeInput.vue'

const bookingStore = useBookingStore()

const isEditableStatus = computed<boolean>(() => bookingStore.availableActions?.isEditable || false)

const bookingDetails = computed<BookingCarRentWithDriverDetails | null>(() => bookingStore.booking?.details || null)

const handleChangeDetails = async (field: string, value: any) => {
  await bookingStore.updateDetails(field, value)
}

const handleChangeTimeForPeriod = async (field: string, value: any) => {
  if (!bookingDetails.value?.bookingPeriod?.dateFrom || !bookingDetails.value?.bookingPeriod?.dateTo) return
  await bookingStore.updateDetails(field, {
    dateFrom: formatDateTimeToAPIDateTime(`${parseAPIDateAndSetDefaultTime(bookingDetails.value.bookingPeriod.dateFrom).toFormat('yyyy-LL-dd')} ${value}`),
    dateTo: formatDateTimeToAPIDateTime(`${parseAPIDateAndSetDefaultTime(bookingDetails.value.bookingPeriod.dateTo).toFormat('yyyy-LL-dd')} ${value}`),
  })
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
              <EditableTextInput
                :value="bookingDetails?.city?.name"
                :can-edit="false"
                type="text"
              />
            </td>
          </tr>
          <tr>
            <th>Период бронирования</th>
            <td>
              <EditableDateRangeInput
                :value="bookingDetails?.bookingPeriod"
                :can-edit="isEditableStatus"
                @change="value => handleChangeDetails('bookingPeriod', value)"
              />
            </td>
          </tr>
          <tr>
            <th>Время подачи авто</th>
            <td>
              <EditableTimeInput
                :value="bookingDetails?.bookingPeriod?.dateFrom"
                :can-edit="isEditableStatus && !!bookingDetails?.bookingPeriod"
                :return-only-time="true"
                @change="value => handleChangeTimeForPeriod('bookingPeriod', value)"
              />
            </td>
          </tr>
          <tr>
            <th>Место подачи авто</th>
            <td>
              <EditableTextInput
                :value="bookingDetails?.meetingAddress"
                :can-edit="isEditableStatus"
                type="text"
                @change="value => handleChangeDetails('meetingAddress', value)"
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
