<script setup lang="ts">

import { computed, MaybeRef, ref, unref, watch } from 'vue'

import { z } from 'zod'

import { BookingTransferFromAirportDetails } from '~resources/views/booking/services/show/components/details/lib/types'
import CarModal from '~resources/views/booking/shared/components/CarModal.vue'
import CarsTable from '~resources/views/booking/shared/components/CarsTable.vue'
import InfoBlock from '~resources/views/booking/shared/components/InfoBlock/InfoBlock.vue'
import InfoBlockTitle from '~resources/views/booking/shared/components/InfoBlock/InfoBlockTitle.vue'
import { CarFormData } from '~resources/views/booking/shared/lib/data-types'
import { useBookingStore } from '~resources/views/booking/shared/store/booking'
import { useEditableModal } from '~resources/views/hotel/settings/composables/editable-modal'

import { CarBid } from '~api/booking/service'
import { addBookingCar, deleteBookingCar, updateBookingCar } from '~api/booking/service/cars'
import { Car, useGetSupplierCarsAPI } from '~api/supplier/cars'

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

const isEditableStatus = computed<boolean>(() => bookingStore.availableActions?.isEditable || false)

const bookingDetails = computed<BookingTransferFromAirportDetails | null>(() => bookingStore.booking?.details || null)

const availableCars = ref<Car[]>([])

const getDefaultCarForm = () => ({})
const carForm = ref<Partial<CarFormData>>(getDefaultCarForm())

const modalSettings = {
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
} = useEditableModal<Required<CarFormData>, Required<CarFormData>, CarBid>(modalSettings)

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

const handleChangeDetails = async (field: string, value: any) => {
  await bookingStore.updateDetails(field, value)
}

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
  openEditCarModal(id, object)
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
            <th>Аэропорт прилёта</th>
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
            <th>Дата прилёта</th>
            <td>
              <EditableDateInput
                :value="bookingDetails?.arrivalDate"
                :can-edit="isEditableStatus"
                @change="value => handleChangeDetails('arrivalDate', value)"
              />
            </td>
          </tr>
          <tr>
            <th>Время прилёта</th>
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

    <InfoBlock>
      <template #header>
        <div class="d-flex gap-1 align-items-center">
          <InfoBlockTitle title="Список автомобилей" />
          <IconButton v-if="isEditableStatus" icon="add" @click="handleOpenCarModal" />
        </div>
      </template>
      <CarsTable
        :can-edit="isEditableStatus"
        :booking-cars="bookingDetails?.carBids || []"
        @edit="(car) => handleEditCarModal(car.id, car)"
        @delete="(car) => handleDeleteCar(car.id)"
      />
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
    </InfoBlock>
  </div>
</template>
