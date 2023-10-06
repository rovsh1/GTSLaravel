<script setup lang="ts">

import { computed, onMounted, ref, unref, watch } from 'vue'

import { MaybeRef } from '@vueuse/core'
import { z } from 'zod'

import GuestModal from '~resources/views/booking/components/GuestModal.vue'
import GuestsTable from '~resources/views/booking/components/GuestsTable.vue'
import InfoBlock from '~resources/views/booking/components/InfoBlock/InfoBlock.vue'
import InfoBlockTitle from '~resources/views/booking/components/InfoBlock/InfoBlockTitle.vue'
import { GuestFormData } from '~resources/views/booking/lib/data-types'
import { useOrderStore } from '~resources/views/booking/store/order'
import { useEditableModal } from '~resources/views/hotel/settings/composables/editable-modal'
import { useBookingStore } from '~resources/views/transfer-booking/show/store/booking'

import { addOrderGuest, Guest, updateOrderGuest } from '~api/booking/order/guest'
import { addBookingGuest, deleteBookingGuest } from '~api/booking/transfer/guests'
import { useCountrySearchAPI } from '~api/country'

import { showConfirmDialog } from '~lib/confirm-dialog'
import { requestInitialData } from '~lib/initial-data'

import Card from '~components/Bootstrap/BootstrapCard/BootstrapCard.vue'
import CardTitle from '~components/Bootstrap/BootstrapCard/components/BootstrapCardTitle.vue'
import IconButton from '~components/IconButton.vue'

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

onMounted(() => {
  fetchCountries()
})

const modalSettings = {
  add: {
    title: 'Добавление гостя',
    handler: async (request: MaybeRef<Required<GuestFormData>>) => {
      const preparedRequest = unref(request)
      if (preparedRequest && preparedRequest.id !== undefined) {
        const payload = { bookingID, guestId: preparedRequest.id }
        await addBookingGuest(payload)
      } else {
        const payload = { airportBookingId: bookingID, ...preparedRequest }
        payload.orderId = orderId.value
        await addOrderGuest(payload)
      }
      await bookingStore.fetchBooking()
      await orderStore.fetchGuests()
    },
  },
  edit: {
    title: 'Редактирование гостя',
    handler: async (request: MaybeRef<Required<GuestFormData>>) => {
      const preparedRequest = unref(request)
      const payload = { guestId: preparedRequest.id, ...preparedRequest }
      payload.orderId = orderId.value
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
</script>

<template>
  <GuestModal
    v-if="countries"
    :title-text="guestModalTitle"
    :opened="isGuestModalOpened"
    :is-fetching="isGuestModalLoading"
    :form-data="guestForm"
    :order-guests="orderGuests"
    :countries="countries"
    @close="closeGuestModal"
    @submit="submitGuestModal"
    @clear="guestForm = getDefaultGuestForm()"
  />

  <Card>
    <CardTitle class="mr-4" title="Информация о брони" />
    <div class="d-flex gap-4">
      <InfoBlock>
        <template #header>
          <div class="d-flex gap-1">
            <InfoBlockTitle title="Гости" />
            <IconButton v-if="isEditableStatus" icon="add" @click="openAddGuestModal" />
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
    </div>
  </Card>
</template>

<style lang="scss" scoped>
.total-sum {
  margin-bottom: 0.5rem;
}
</style>
