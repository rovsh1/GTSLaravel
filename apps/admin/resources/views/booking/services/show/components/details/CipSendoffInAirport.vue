<script setup lang="ts">

import { computed, onMounted, ref, unref, watch } from 'vue'

import { MaybeRef } from '@vueuse/core'
import { z } from 'zod'

import { BookingCipSendoffInAirportDetails } from '~resources/views/booking/services/show/components/details/lib/types'
import GuestModal from '~resources/views/booking/shared/components/GuestModal.vue'
import GuestsTable from '~resources/views/booking/shared/components/GuestsTable.vue'
import InfoBlock from '~resources/views/booking/shared/components/InfoBlock/InfoBlock.vue'
import InfoBlockTitle from '~resources/views/booking/shared/components/InfoBlock/InfoBlockTitle.vue'
import { GuestFormData } from '~resources/views/booking/shared/lib/data-types'
import { useBookingStore } from '~resources/views/booking/shared/store/booking'
import { useOrderStore } from '~resources/views/booking/shared/store/order'
import { useEditableModal } from '~resources/views/hotel/settings/composables/editable-modal'

import { addBookingGuest, deleteBookingGuest } from '~api/booking/service/guests'
import { useCountrySearchAPI } from '~api/country'
import { addOrderGuest, Guest, updateOrderGuest } from '~api/order/guest'

import { showConfirmDialog } from '~lib/confirm-dialog'
import { requestInitialData } from '~lib/initial-data'

import EditableDateInput from '~components/Editable/EditableDateInput.vue'
import EditableTextInput from '~components/Editable/EditableTextInput.vue'
import EditableTimeInput from '~components/Editable/EditableTimeInput.vue'
import IconButton from '~components/IconButton.vue'

const { bookingID } = requestInitialData('view-initial-data-service-booking', z.object({
  bookingID: z.number(),
}))

const bookingStore = useBookingStore()
const orderStore = useOrderStore()
const orderId = computed(() => orderStore.order.id)
const orderGuests = computed<Guest[]>(() => orderStore.guests || [])

const bookingDetails = computed<BookingCipSendoffInAirportDetails | null>(() => bookingStore.booking?.details || null)

const bookingGuestsIds = computed<number[]>(() => bookingDetails.value?.guestIds || [])

const filteredOrderGuests = computed<Guest[]>(() => orderGuests.value.filter((guest) =>
  !bookingGuestsIds.value.includes(guest.id)))

const isEditableStatus = computed<boolean>(() => bookingStore.availableActions?.isEditable || false)

const { data: countries, execute: fetchCountries } = useCountrySearchAPI()

onMounted(() => {
  fetchCountries()
})

const modalSettings = {
  add: {
    title: 'Добавление гостя',
    handler: async (request: MaybeRef<Required<GuestFormData>>): Promise<boolean> => {
      let isSuccessesRequest = false
      const preparedRequest = unref(request)
      if (preparedRequest && preparedRequest.id !== undefined) {
        const payload = { bookingID, guestId: preparedRequest.id }
        const response = await addBookingGuest(payload)
        isSuccessesRequest = response.data.value?.success || false
      } else {
        const payload = { airportBookingId: bookingID, ...preparedRequest }
        payload.orderId = orderId.value
        const response = await addOrderGuest(payload)
        isSuccessesRequest = !!response.data.value?.id || false
      }
      await bookingStore.fetchBooking()
      await orderStore.fetchGuests()
      return isSuccessesRequest
    },
  },
  edit: {
    title: 'Редактирование гостя',
    handler: async (request: MaybeRef<Required<GuestFormData>>): Promise<boolean> => {
      const preparedRequest = unref(request)
      const payload = { guestId: preparedRequest.id, ...preparedRequest }
      payload.orderId = orderId.value
      const response = await updateOrderGuest(payload)
      await orderStore.fetchGuests()
      return response.data.value?.success || false
    },
  },
}

const {
  isOpened: isGuestModalOpened,
  isLoading: isGuestModalLoading,
  title: guestModalTitle,
  openAdd: openAddGuestModal,
  openEdit: openEditGuestModal,
  editableObject: editableGuest,
  close: closeGuestModal,
  submit: submitGuestModal,
} = useEditableModal<Required<GuestFormData>, Required<GuestFormData>, Partial<GuestFormData>>(modalSettings)

const getDefaultGuestForm = () => ({ isAdult: true })
const guestForm = ref<Partial<GuestFormData>>(getDefaultGuestForm())

watch(editableGuest, (value) => {
  if (!value) {
    guestForm.value = getDefaultGuestForm()
    guestForm.value.selectedGuestFromOrder = undefined
    return
  }
  guestForm.value = value
})

const handleDeleteGuest = async (guestId: number) => {
  const { result: isConfirmed, toggleLoading, toggleClose } = await showConfirmDialog('Удалить гостя?', 'btn-danger')
  if (isConfirmed) {
    toggleLoading()
    await deleteBookingGuest({ bookingID, guestId })
    await bookingStore.fetchBooking()
    toggleClose()
  }
}

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
            <th>Аэропорт</th>
            <td>
              <EditableTextInput
                :value="bookingDetails?.airportInfo?.name"
                :can-edit="false"
                type="text"
              />
            </td>
          </tr>
          <tr>
            <th>Номер рейса</th>
            <td>
              <EditableTextInput
                :value="bookingDetails?.flightNumber"
                :can-edit="isEditableStatus"
                type="text"
                @change="value => handleChangeDetails('flightNumber', value)"
              />
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

    <InfoBlock>
      <template #header>
        <div class="d-flex gap-1 align-items-center mb-1">
          <InfoBlockTitle title="Список гостей" />
          <IconButton v-if="isEditableStatus" icon="add" @click="openAddGuestModal" />
        </div>
      </template>
      <GuestsTable
        v-if="countries"
        :can-edit="isEditableStatus"
        :guest-ids="bookingGuestsIds"
        :order-guests="orderGuests"
        :countries="countries"
        @edit="guest => openEditGuestModal(guest.id, guest)"
        @delete="(guest) => handleDeleteGuest(guest.id)"
      />
      <GuestModal
        v-if="countries"
        :title-text="guestModalTitle"
        :opened="isGuestModalOpened"
        :is-fetching="isGuestModalLoading"
        :form-data="guestForm"
        :order-guests="filteredOrderGuests"
        :countries="countries"
        @close="closeGuestModal"
        @submit="submitGuestModal"
        @clear="guestForm = getDefaultGuestForm()"
      />
    </InfoBlock>
  </div>
</template>

<style lang="scss" scoped>
.total-sum {
  margin-bottom: 0.5rem;
}
</style>
