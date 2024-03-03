<script setup lang="ts">

import { computed } from 'vue'

import EditableDateInput from 'gts-components/Editable/EditableDateInput'
import EditableTextInput from 'gts-components/Editable/EditableTextInput'

import { BookingOtherDetails } from '~resources/views/booking/services/show/components/details/lib/types'
import InfoBlock from '~resources/views/booking/shared/components/InfoBlock/InfoBlock.vue'
import { useBookingStore } from '~resources/views/booking/shared/store/booking'

const bookingStore = useBookingStore()

const isEditableStatus = computed<boolean>(() => bookingStore.availableActions?.isEditable || false)

const bookingDetails = computed<BookingOtherDetails | null>(() => bookingStore.booking?.details || null)

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
            <th>Дата</th>
            <td>
              <EditableDateInput
                :value="bookingDetails?.date"
                :can-edit="isEditableStatus"
                @change="value => handleChangeDetails('date', value)"
              />
            </td>
          </tr>
          <tr>
            <th>Примечание</th>
            <td>
              <EditableTextInput
                :value="bookingDetails?.description"
                :can-edit="isEditableStatus"
                type="text"
                @change="value => handleChangeDetails('description', value)"
              />
            </td>
          </tr>
        </tbody>
      </table>
    </InfoBlock>
  </div>
</template>
