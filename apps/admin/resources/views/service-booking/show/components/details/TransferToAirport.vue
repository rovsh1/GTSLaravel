<script setup lang="ts">

import { computed, MaybeRef, ref, unref } from 'vue'

import CarModal from '~resources/views/booking/components/CarModal.vue'
import CarsTable from '~resources/views/booking/components/CarsTable.vue'
import InfoBlock from '~resources/views/booking/components/InfoBlock/InfoBlock.vue'
import InfoBlockTitle from '~resources/views/booking/components/InfoBlock/InfoBlockTitle.vue'
import { CarFormData } from '~resources/views/booking/lib/data-types'
import { useEditableModal } from '~resources/views/hotel/settings/composables/editable-modal'
import { useBookingStore } from '~resources/views/service-booking/show/store/booking'

import { BookingDetails } from '~api/booking/service'

import { formatDateToAPIDate, parseAPIDateToJSDate } from '~lib/date'

import EditableDateInput from '~components/Editable/EditableDateInput.vue'
import EditableTextInput from '~components/Editable/EditableTextInput.vue'
import IconButton from '~components/IconButton.vue'

const bookingStore = useBookingStore()

const isEditableStatus = computed<boolean>(() => bookingStore.availableActions?.isEditable || false)

const bookingDetails = computed<BookingDetails | null>(() => bookingStore.booking?.details || null)

const getDefaultCarForm = () => ({ })
const carForm = ref<Partial<CarFormData>>(getDefaultCarForm())

const modalSettings = {
  add: {
    title: 'Добавление автомобиля',
    handler: async (request: MaybeRef<Required<CarFormData>>) => {
      const preparedRequest = unref(request)
      if (preparedRequest && preparedRequest.id !== undefined) {
        // const payload = { bookingID, guestId: preparedRequest.id }
        // await addBookingGuest(payload)
      } else {
        /// /const payload = { airportBookingId: bookingID, ...preparedRequest }
        // payload.orderId = orderId.value
        // await addOrderGuest(payload)
      }
      // await bookingStore.fetchBooking()
      // await orderStore.fetchGuests()
    },
  },
  edit: {
    title: 'Редактирование автомобиля',
    handler: async (request: MaybeRef<Required<CarFormData>>) => {
      const preparedRequest = unref(request)
      const payload = { guestId: preparedRequest.id, ...preparedRequest }
      // payload.orderId = orderId.value
      // await updateOrderGuest(payload)
      // await orderStore.fetchGuests()
    },
  },
}

const {
  isOpened: isCarModalOpened,
  isLoading: isCarModalLoading,
  title: carModalTitle,
  openAdd: openAddCarModal,
  openEdit: openEditCarModal,
  editableObject: editableCar,
  close: closeCarModal,
  submit: submitCarModal,
} = useEditableModal<Required<CarFormData>, Required<CarFormData>, Partial<CarFormData>>(modalSettings)

const handleChangeDetails = async (field: string, value: any) => {
  await bookingStore.updateDetails(field, value)
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
            <th>Номер рейса</th>
            <td>
              <EditableTextInput :value="bookingDetails?.flightNumber" type="text" />
            </td>
          </tr>
          <tr>
            <th>Дата вылета</th>
            <td>
              <EditableDateInput
                :value="bookingDetails?.departureDate
                  ? parseAPIDateToJSDate(bookingDetails?.departureDate) : undefined"
                @change="value => handleChangeDetails('departure_date', value ? formatDateToAPIDate(value) : null)"
              />
            </td>
          </tr>
          <tr>
            <th>Время вылета</th>
            <td>
              <EditableTextInput
                :value="''"
                type="time"
              />
            </td>
          </tr>
          <tr>
            <th>Город вылета</th>
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
        </tbody>
      </table>
    </InfoBlock>

    <InfoBlock>
      <template #header>
        <div class="d-flex gap-1">
          <InfoBlockTitle title="Список автомобилей" />
          <IconButton v-if="isEditableStatus" icon="add" @click="openAddCarModal" />
        </div>
      </template>
      <CarsTable
        :can-edit="isEditableStatus"
        :car-ids="[]"
        :order-cars="[]"
        @edit="(car) => { }"
        @delete="(car) => { }"
      />
      <CarModal
        :title-text="carModalTitle"
        :opened="isCarModalOpened"
        :is-fetching="isCarModalLoading"
        :form-data="carForm"
        :order-cars="[]"
        :cars="[]"
        @close="closeCarModal"
        @submit="submitCarModal"
        @clear="carForm = getDefaultCarForm()"
      />
    </InfoBlock>
  </div>
</template>
