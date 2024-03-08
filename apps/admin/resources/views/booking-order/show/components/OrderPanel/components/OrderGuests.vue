<script setup lang="ts">
import { computed, MaybeRef, nextTick, ref, unref, watch } from 'vue'

import { showConfirmDialog } from 'gts-common/helpers/confirm-dialog'
import IconButton from 'gts-components/Base/IconButton'
import { storeToRefs } from 'pinia'

import GuestModal from '~resources/views/booking/shared/components/GuestModal.vue'
import GuestsTable from '~resources/views/booking/shared/components/GuestsTable.vue'
import InfoBlock from '~resources/views/booking/shared/components/InfoBlock/InfoBlock.vue'
import InfoBlockTitle from '~resources/views/booking/shared/components/InfoBlock/InfoBlockTitle.vue'
import { GuestFormData } from '~resources/views/booking/shared/lib/data-types'
import { useOrderStore } from '~resources/views/booking-order/show/store/order'
import { useEditableModal } from '~resources/views/hotel/settings/composables/editable-modal'

import { addOrderGuest, deleteOrderGuest, updateOrderGuest } from '~api/order/guest'

import { useCountryStore } from '~stores/countries'

const { countries } = storeToRefs(useCountryStore())
const orderStore = useOrderStore()
const orderId = computed(() => orderStore.order?.id)
const isEditableStatus = computed<boolean>(() => orderStore.availableActions?.isEditable || false)
const orderGuests = computed(() => orderStore.guests)
const orderGuestsIds = computed(() => orderStore.guests?.map((guest) => guest.id))
const getDefaultGuestForm = () => ({ isAdult: true })

const modalSettings = {
  add: {
    title: 'Добавление гостя',
    handler: async (request: MaybeRef<Required<GuestFormData>>): Promise<boolean> => {
      if (!orderId.value) return false
      const preparedRequest = unref(request)
      let isSuccessesRequest = false
      const payload = { ...preparedRequest }
      payload.orderId = orderId.value
      const response = await addOrderGuest(payload)
      isSuccessesRequest = !!response.data.value?.id || false
      await orderStore.refreshOrder()
      return isSuccessesRequest
    },
  },
  edit: {
    title: 'Редактирование гостя',
    handler: async (request: MaybeRef<Required<GuestFormData>>): Promise<boolean> => {
      if (!orderId.value) return false
      const preparedRequest = unref(request)
      const payload = { guestId: preparedRequest.id, ...preparedRequest }
      payload.orderId = orderId.value
      const response = await updateOrderGuest(payload)
      await orderStore.refreshOrder()
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
  const message = 'Удаление гостя из заказа приведет к возврату всех подтвержденных броней с этим гостем в рабочий статус.'
  const { result: isConfirmed, toggleLoading, toggleClose } = await showConfirmDialog(message, 'btn-danger', 'Удаление гостя')
  if (!isConfirmed) {
    return
  }
  toggleLoading()
  await deleteOrderGuest({ guestId, orderId: orderId.value as number })
  nextTick(orderStore.refreshOrder)
  nextTick(toggleClose)
}

</script>

<template>
  <GuestModal
    v-if="countries"
    :title-text="guestModalTitle"
    :opened="isGuestModalOpened"
    :is-fetching="isGuestModalLoading"
    :form-data="guestForm"
    :countries="countries"
    @close="closeGuestModal()"
    @submit="submitGuestModal"
    @clear="guestForm = getDefaultGuestForm()"
  />
  <InfoBlock>
    <template #header>
      <div class="d-flex gap-1 align-items-center mb-1">
        <InfoBlockTitle class="mb-0" title="Список гостей" />
        <IconButton
          v-if="isEditableStatus"
          icon="add"
          @click="() => openAddGuestModal()"
        />
      </div>
    </template>

    <GuestsTable
      v-if="countries"
      :can-edit="isEditableStatus"
      :guest-ids="orderGuestsIds"
      :order-guests="orderGuests || []"
      :countries="countries"
      @edit="(guest) => openEditGuestModal(guest.id, guest)"
      @delete="(guest) => handleDeleteGuest(guest.id)"
    />
  </InfoBlock>
</template>
