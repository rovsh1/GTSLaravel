<script setup lang="ts">

import { computed, onMounted, ref, unref, watch } from 'vue'

import { MaybeRef } from '@vueuse/core'
import { z } from 'zod'

import GuestModal from '~resources/views/airport-booking/show/components/GuestModal.vue'
import { useBookingStore } from '~resources/views/airport-booking/show/store/booking'
import { useEditableModal } from '~resources/views/hotel/settings/composables/editable-modal'
import GuestsTable from '~resources/views/hotel-booking/show/components/GuestsTable.vue'
import InfoBlock from '~resources/views/hotel-booking/show/components/InfoBlock/InfoBlock.vue'
import InfoBlockTitle from '~resources/views/hotel-booking/show/components/InfoBlock/InfoBlockTitle.vue'
import { GuestFormData } from '~resources/views/hotel-booking/show/lib/data-types'
import { useOrderStore } from '~resources/views/hotel-booking/show/store/order'

import { deleteBookingGuest } from '~api/booking/airport/guests'
import { addOrderGuest, Guest, updateOrderGuest } from '~api/booking/order/guest'
import { useCountrySearchAPI } from '~api/country'

import { showConfirmDialog } from '~lib/confirm-dialog'
import { requestInitialData } from '~lib/initial-data'

import Card from '~components/Bootstrap/BootstrapCard/BootstrapCard.vue'
import CardTitle from '~components/Bootstrap/BootstrapCard/components/BootstrapCardTitle.vue'
import IconButton from '~components/IconButton.vue'

const { bookingID } = requestInitialData('view-initial-data-airport-booking', z.object({
  bookingID: z.number(),
  // manager: z.object({
  //   id: z.number(),
  // }),
}))

const bookingStore = useBookingStore()
const orderStore = useOrderStore()
const orderGuests = computed<Guest[]>(() => orderStore.guests || [])
const booking = computed(() => bookingStore.booking)
const isEditableStatus = computed(() => true)
const { data: countries, execute: fetchCountries } = useCountrySearchAPI()

onMounted(() => {
  fetchCountries()
})

const modalSettings = {
  add: {
    title: 'Добавление туриста',
    handler: async (request: MaybeRef<Required<GuestFormData>>) => {
      const preparedRequest = unref(request)
      const payload = { airportBookingId: bookingID, ...preparedRequest }
      await addOrderGuest(payload)
      await orderStore.fetchGuests()
      await bookingStore.fetchBooking()
    },
  },
  edit: {
    title: 'Изменение туриста',
    handler: async (request: MaybeRef<Required<GuestFormData>>) => {
      const preparedRequest = unref(request)
      const payload = { guestId: preparedRequest.id, ...preparedRequest }
      await updateOrderGuest(payload)
      await orderStore.fetchGuests()
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

</script>

<template>
  <GuestModal
    v-if="countries"
    :opened="isGuestModalOpened"
    :loading="isGuestModalLoading"
    :title="guestModalTitle"
    :countries="countries"
    :form-data="guestForm"
    @close="closeGuestModal"
    @submit="submitGuestModal"
  />

  <Card>
    <CardTitle class="mr-4" title="Информация о брони" />

    <div class="d-flex gap-4">
      <InfoBlock>
        <template #header>
          <div class="d-flex gap-1">
            <InfoBlockTitle title="Гости" />
            <IconButton
              v-if="isEditableStatus"
              icon="add"
              @click="openAddGuestModal"
            />
          </div>
        </template>

        <GuestsTable
          v-if="countries"
          :can-edit="isEditableStatus"
          :guest-ids="booking?.guestIds"
          :order-guests="orderGuests"
          :countries="countries"
          @edit="guest => openEditGuestModal(guest.id, guest)"
          @delete="(guest) => handleDeleteGuest(guest.id)"
        />
      </InfoBlock>

      <InfoBlock>
        <template #header>
          <InfoBlockTitle title="Стоимость брони" />
        </template>
      </InfoBlock>
    </div>
  </Card>
</template>
