<script setup lang="ts">

import { computed, MaybeRef, ref, unref, watch } from 'vue'

import { storeToRefs } from 'pinia'
import { z } from 'zod'

import { BookingTransferFromAirportDetails } from '~resources/views/booking/services/show/components/details/lib/types'
import CarModal from '~resources/views/booking/shared/components/CarModal.vue'
import GuestModal from '~resources/views/booking/shared/components/GuestModal.vue'
import GuestsTable from '~resources/views/booking/shared/components/GuestsTable.vue'
import InfoBlock from '~resources/views/booking/shared/components/InfoBlock/InfoBlock.vue'
import InfoBlockTitle from '~resources/views/booking/shared/components/InfoBlock/InfoBlockTitle.vue'
import { CarFormData, GuestFormData } from '~resources/views/booking/shared/lib/data-types'
import { useBookingStore } from '~resources/views/booking/shared/store/booking'
import { useOrderStore } from '~resources/views/booking/shared/store/order'
import EditTableRowButton from '~resources/views/hotel/settings/components/EditTableRowButton.vue'
import { useEditableModal } from '~resources/views/hotel/settings/composables/editable-modal'

import { CarBid } from '~api/booking/service'
import { addBookingCar, deleteBookingCar, updateBookingCar } from '~api/booking/service/cars'
import { addGuestToCar, deleteGuestFromCar } from '~api/booking/service/guests'
import { addOrderGuest, Guest, updateOrderGuest } from '~api/order/guest'
import { Car, useGetSupplierCarsAPI } from '~api/supplier/cars'

import BootstrapButton from '~components/Bootstrap/BootstrapButton/BootstrapButton.vue'
import BootstrapCard from '~components/Bootstrap/BootstrapCard/BootstrapCard.vue'
import BootstrapCardTitle from '~components/Bootstrap/BootstrapCard/components/BootstrapCardTitle.vue'
import { showToast } from '~components/Bootstrap/BootstrapToast'
import EmptyData from '~components/EmptyData.vue'
import IconButton from '~components/IconButton.vue'

import { useCountryStore } from '~stores/countries'

import { showConfirmDialog } from '~helpers/confirm-dialog'
import { requestInitialData } from '~helpers/initial-data'
import { pluralForm } from '~helpers/plural'

const { bookingID } = requestInitialData(z.object({
  bookingID: z.number(),
}))

const orderStore = useOrderStore()

const orderId = computed(() => orderStore.order.id)
const orderGuests = computed<Guest[]>(() => orderStore.guests || [])

const bookingStore = useBookingStore()

const isEditableStatus = computed<boolean>(() => bookingStore.availableActions?.isEditable || false)

const bookingDetails = computed<BookingTransferFromAirportDetails | null>(() => bookingStore.booking?.details || null)

const availableCars = ref<Car[]>([])

const { countries } = storeToRefs(useCountryStore())

const getDefaultCarForm = () => ({})
const carForm = ref<Partial<CarFormData>>(getDefaultCarForm())

const editingCar = ref<CarBid>()

const carModalSettings = {
  add: {
    title: 'Добавление автомобиля',
    handler: async (request: MaybeRef<Required<CarFormData>>): Promise<boolean> => {
      const preparedRequest = unref(request)
      preparedRequest.baggageCount = preparedRequest.baggageCount || 0
      const payload = { bookingID, ...preparedRequest, babyCount: 0 }
      const response = await addBookingCar(payload)
      await bookingStore.fetchBooking()
      return response.data.value?.success || false
    },
  },
  edit: {
    title: 'Редактирование автомобиля',
    handler: async (request: MaybeRef<Required<CarFormData>>): Promise<boolean> => {
      const preparedRequest = unref(request)
      if (editingCar.value && preparedRequest.passengersCount * preparedRequest.carsCount < editingCar.value.guestIds.length) {
        showToast({ title: 'Количество добавленных гостей превышает вместимость автомобилей' }, {
          type: 'warning',
        })
        return false
      }
      preparedRequest.baggageCount = preparedRequest.baggageCount || 0
      const payload = { bookingID, ...preparedRequest, babyCount: 0 }
      const response = await updateBookingCar(payload)
      await bookingStore.fetchBooking()
      return response.data.value?.success || false
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
} = useEditableModal<Required<CarFormData>, Required<CarFormData>, CarBid>(carModalSettings)

watch(editableCar, (value) => {
  if (!value) {
    carForm.value = getDefaultCarForm()
    return
  }
  carForm.value = {
    id: value.id,
    carId: value.carInfo.id,
    carsCount: value.carsCount,
    passengersCount: value.passengersCount,
    baggageCount: value.baggageCount,
    babyCount: value.babyCount,
  }
})

const getDefaultGuestForm = () => ({ isAdult: true })

const editCarBookingId = ref<number>()

const guestModalSettings = {
  add: {
    title: 'Добавление гостя',
    handler: async (request: MaybeRef<Required<GuestFormData>>): Promise<boolean> => {
      let isSuccessesRequest = false
      if (!editCarBookingId.value) return false
      const preparedRequest = unref(request)
      if (preparedRequest && preparedRequest.id !== undefined) {
        const payload = { bookingID, carBidId: editCarBookingId.value, guestId: preparedRequest.id }
        const response = await addGuestToCar(payload)
        isSuccessesRequest = response.data.value?.success || false
      } else {
        const payload = { carBidBookingId: bookingID, carBidId: editCarBookingId.value, ...preparedRequest }
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
} = useEditableModal<Required<GuestFormData>, Required<GuestFormData>, Partial<GuestFormData>>(guestModalSettings)

const guestForm = ref<Partial<GuestFormData>>(getDefaultGuestForm())

watch(editableGuest, (value) => {
  if (!value) {
    guestForm.value = getDefaultGuestForm()
    guestForm.value.selectedGuestFromOrder = undefined
    return
  }
  guestForm.value = value
})

const handleDeleteGuest = async (roomBookingId: number, guestId: number): Promise<void> => {
  const { result: isConfirmed, toggleLoading, toggleClose } = await showConfirmDialog('Удалить гостя?', 'btn-danger')
  if (isConfirmed) {
    toggleLoading()
    await deleteGuestFromCar({ bookingID, carBidId: roomBookingId, guestId })
    await bookingStore.fetchBooking()
    toggleClose()
  }
}

const bookingCarsGuestsIds = computed<number[]>(() => bookingDetails.value?.carBids.flatMap(
  (car) => car.guestIds || [],
) || [])

const filteredOrderGuests = computed<Guest[]>(() => orderGuests.value.filter((guest) =>
  !bookingCarsGuestsIds.value.includes(guest.id)))

const getAvailableCars = async (filtered?: boolean) => {
  const supplierId = bookingDetails.value?.serviceInfo.supplierId
  const carsInBooking = bookingDetails.value?.carBids
  if (!supplierId) {
    availableCars.value = []
  } else {
    const { data: supplierCars, execute: fetchSupplierCars } = useGetSupplierCarsAPI({ supplierId })
    await fetchSupplierCars()
    if (supplierCars.value) {
      if (filtered) {
        const carsInBookingIds = carsInBooking?.map((car) => car.carInfo.id) || []
        const filteredAvailableCars = supplierCars.value.filter((supplierCar) => !carsInBookingIds.includes(supplierCar.id))
        availableCars.value = filteredAvailableCars
      } else {
        availableCars.value = supplierCars.value
      }
    } else {
      availableCars.value = []
    }
  }
}

const handleDeleteCar = async (carId: number) => {
  const { result: isConfirmed, toggleLoading, toggleClose } = await showConfirmDialog('Удалить автомобиль?', 'btn-danger')
  if (isConfirmed) {
    toggleLoading()
    await deleteBookingCar({ bookingID, id: carId })
    await bookingStore.fetchBooking()
    toggleClose()
  }
}

const handleOpenCarModal = async () => {
  await getAvailableCars(true)
  openAddCarModal()
}

const handleEditCarModal = async (id: number, object: CarBid) => {
  await getAvailableCars()
  editingCar.value = object
  openEditCarModal(id, object)
}
</script>

<template>
  <CarModal
    :title-text="carModalTitle"
    :opened="isCarModalOpened"
    :is-fetching="isCarModalLoading"
    :form-data="carForm"
    :available-cars="availableCars"
    @close="closeCarModal"
    @submit="submitCarModal"
    @clear="carForm = getDefaultCarForm()"
  />

  <GuestModal
    v-if="countries"
    :title-text="guestModalTitle"
    :opened="isGuestModalOpened"
    :is-fetching="isGuestModalLoading"
    :form-data="guestForm"
    :order-guests="filteredOrderGuests"
    :countries="countries"
    @close="closeGuestModal()"
    @submit="submitGuestModal"
    @clear="guestForm = getDefaultGuestForm()"
  />
  <BootstrapCard v-for="(car, index) in bookingDetails?.carBids" :key="car.id">
    <div class="d-flex align-items-start">
      <BootstrapCardTitle class="mr-4" :title="`#${index + 1} ${car.carInfo.mark} ${car.carInfo.model}`" />
      <div class="pt-card-title">
        <EditTableRowButton
          v-if="isEditableStatus"
          @edit="() => handleEditCarModal(car.id, car)"
          @delete="() => handleDeleteCar(car.id)"
        />
      </div>
    </div>
    <div class="d-flex flex-row gap-4">
      <InfoBlock>
        <template #header>
          <InfoBlockTitle class="mb-3" title="Дополнительные параметры" />
        </template>
        <table class="table-params">
          <tbody>
            <tr>
              <th>Количествово автомобилей</th>
              <td>
                {{ car.carsCount || 'Не указано' }}
                <template v-if="car.carsCount > 1">
                  (по {{ car.passengersCount }} {{ pluralForm(car.passengersCount, ['гостю', 'гостя', 'гостей']) }})
                </template>
                <template v-else>
                  ({{ car.passengersCount }} {{ pluralForm(car.passengersCount, ['гость', 'гостя', 'гостей']) }})
                </template>
              </td>
            </tr>
            <tr>
              <th>Количествово багажа</th>
              <td>
                {{ car.baggageCount || 'Не указано' }}
                <template v-if="car.baggageCount && car.carsCount > 1">
                  (по {{ car.baggageCount }} на автомобиль)
                </template>
              </td>
            </tr>
          </tbody>
        </table>
      </InfoBlock>

      <InfoBlock>
        <template #header>
          <div class="d-flex gap-1 align-items-center mb-1">
            <InfoBlockTitle title="Список гостей" />
            <IconButton
              v-if="isEditableStatus
                && (car.passengersCount && car.guestIds.length < (car.passengersCount * car.carsCount))"
              icon="add"
              @click="() => {
                editCarBookingId = car.id
                openAddGuestModal()
              }"
            />
          </div>
        </template>

        <GuestsTable
          v-if="countries"
          :can-edit="isEditableStatus"
          :guest-ids="car.guestIds"
          :order-guests="orderGuests"
          :countries="countries"
          @edit="(guest) => {
            editCarBookingId = car.id
            openEditGuestModal(guest.id, guest)
          }"
          @delete="(guest) => { handleDeleteGuest(car.id, guest.id) }"
        />
      </InfoBlock>
    </div>
  </BootstrapCard>
  <div>
    <BootstrapButton
      v-if="isEditableStatus && bookingDetails?.carBids.length"
      label="Добавить автомобиль"
      @click="handleOpenCarModal"
    />
    <div>
      <EmptyData v-if="!bookingDetails?.carBids.length">
        Забронированные автомобили отсутствуют
        <br v-if="isEditableStatus">
        <br v-if="isEditableStatus">
        <BootstrapButton v-if="isEditableStatus" label="Добавить автомобиль" @click="handleOpenCarModal" />
      </EmptyData>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.pt-card-title {
  margin-top: 0.93rem;
}
</style>
