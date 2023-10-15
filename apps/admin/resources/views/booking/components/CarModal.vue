<script setup lang="ts">

import { computed, nextTick, ref, watchEffect } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { isDataValid } from '~resources/composables/form'
import { CarFormData } from '~resources/views/booking/lib/data-types'

import { Car } from '~api/booking/order/cars'
import { CarResponse } from '~api/cars'

import BaseDialog from '~components/BaseDialog.vue'
import BootstrapSelectBase from '~components/Bootstrap/BootstrapSelectBase.vue'
import { SelectOption } from '~components/Bootstrap/lib'
import Select2BaseSelect from '~components/Select2BaseSelect.vue'

const props = withDefaults(defineProps<{
  opened: MaybeRef<boolean>
  isFetching: MaybeRef<boolean>
  cars: CarResponse[]
  formData: Partial<CarFormData>
  orderCars?: Car[] | null
  titleText?: string
  inputSelectText?: string
}>(), {
  titleText: 'Данные автомобиля',
  inputSelectText: 'Автомобиль',
  orderCars: null,
})

const emit = defineEmits<{
  (event: 'close'): void
  (event: 'clear'): void
  (event: 'submit', payload: any): void
}>()

const setFormData = () => ({
  id: props.formData.id,
  ...props.formData,
  selectedCarFromOrder: undefined,
})

const formData = ref<Partial<CarFormData>>(setFormData())

watchEffect(() => {
  formData.value = setFormData()
})

const carsCountValidate = (): boolean => ((formData.value.carCount !== undefined && formData.value.carCount !== null
&& formData.value?.carCount > 0))

const validateCreateCarForm = computed(() => (isDataValid(null, formData.value.carModelId)
&& isDataValid(null, formData.value.passengerCount) && isDataValid(null, formData.value.carCount, carsCountValidate())
&& isDataValid(null, formData.value.baggageCount)))

const validateSelectCarForm = computed(() => (isDataValid(null, formData.value.selectedCarFromOrder)))

const isFormValid = (): boolean => {
  if (
    ((validateCreateCarForm.value || validateSelectCarForm.value) && formData.value.id === undefined)
        || (formData.value.id !== undefined && validateCreateCarForm.value)
  ) {
    return true
  }
  return false
}

const submitDisable = computed(() => !isFormValid())

const onModalSubmit = async () => {
  if (!isFormValid()) {
    return
  }
  if (formData.value.id !== undefined) {
    const payload = {
      guestId: formData.value.id,
      ...formData.value,
    }
    emit('submit', payload)
  } else if (formData.value.selectedCarFromOrder !== undefined) {
    const payload = {
      id: formData.value.selectedCarFromOrder,
    }
    emit('submit', payload)
  } else if (formData.value.selectedCarFromOrder === undefined) {
    const payload = {
      ...formData.value,
    }
    emit('submit', payload)
  }
}

const carsOptions = computed<SelectOption[]>(
  () => props.cars?.map((car: CarResponse) => ({ value: car.id, label: car.name })) || [],
)

const orderCarsOptions = computed<SelectOption[]>(
  () => props.orderCars?.map((car: Car) => ({ value: car.id, label: car.carModel.toString() })) || [],
)

const resetForm = () => {
  emit('clear')
  formData.value.carModelId = undefined
  formData.value.carCount = undefined
  formData.value.passengerCount = undefined
  formData.value.baggageCount = undefined
  formData.value.selectedCarFromOrder = undefined
  nextTick(() => {
    $('.is-invalid').removeClass('is-invalid')
  })
}

const closeModal = () => {
  resetForm()
  emit('close')
  emit('clear')
}

const onChangeSelectGuest = (value: any) => {
  formData.value.selectedCarFromOrder = value ? Number(value) : undefined
  const getCurrentCarData = props.orderCars?.filter((car: Car) => car.id === formData.value.selectedCarFromOrder)
  if (getCurrentCarData && getCurrentCarData.length > 0) {
    const [firstCar] = getCurrentCarData
    formData.value.carModelId = firstCar.carModel
    formData.value.carCount = firstCar.carCount
    formData.value.passengerCount = firstCar.passengerCount
    formData.value.baggageCount = firstCar.baggageCount
  } else {
    resetForm()
  }
}

</script>

<template>
  <BaseDialog
    :opened="opened as boolean"
    :loading="isFetching"
    @close="closeModal"
    @keydown.enter="onModalSubmit"
  >
    <template #title>{{ titleText }}</template>
    <form class="row g-3">
      <div v-if="orderCars && orderCars.length > 0 && formData.id === undefined" class="col-md-12 car-select-wrapper">
        <Select2BaseSelect
          id="car-select"
          :label="inputSelectText"
          :options="orderCarsOptions"
          :value="formData.selectedCarFromOrder"
          parent=".car-select-wrapper"
          :enable-tags="false"
          :show-empty-item="true"
          empty-item-text="Добавить новый автомобиль"
          @input="onChangeSelectGuest"
        />
      </div>
      <div class="col-md-12">
        <BootstrapSelectBase
          id="car_id"
          :options="carsOptions"
          label="Модель автомобиля"
          :disabled="!!formData.selectedCarFromOrder"
          :value="formData.carModelId as number"
          required
          @input="(value: any, event: any) => {
            formData.carModelId = value as number
            isDataValid(event, value)
          }"
        />
      </div>
      <div class="col-md-12">
        <div class="field-required">
          <label for="cars_count">Количествово автомобилей</label>
          <input
            id="cars_count"
            v-model="formData.carCount"
            :disabled="!!formData.selectedCarFromOrder"
            class="form-control"
            type="number"
            required
            @input="isDataValid($event.target, formData.carCount, carsCountValidate())"
            @blur="isDataValid($event.target, formData.carCount, carsCountValidate())"
          >
        </div>
      </div>
      <div class="col-md-12">
        <BootstrapSelectBase
          id="passenger_count"
          :options="[]"
          label="Количествово пассажиров"
          :disabled="!!formData.selectedCarFromOrder"
          :value="formData.passengerCount as number"
          required
          @input="(value: any, event: any) => {
            formData.passengerCount = value as number
            isDataValid(event, value)
          }"
        />
      </div>
      <div class="col-md-12">
        <BootstrapSelectBase
          id="baggage_count"
          :options="[]"
          label="Количествово багажа"
          :disabled="!!formData.selectedCarFromOrder"
          :value="formData.baggageCount as number"
          :show-empty-item="false"
          @input="(value: any, event: any) => {
            formData.baggageCount = value as number
            isDataValid(event, value)
          }"
        />
      </div>
    </form>
    <template v-if="formData.id === undefined" #actions-start>
      <button class="btn btn-default" type="button" @click="resetForm">
        Сбросить
      </button>
    </template>
    <template #actions-end>
      <button
        class="btn btn-primary"
        type="button"
        :disabled="submitDisable"
        @click="onModalSubmit"
      >
        Сохранить
      </button>
      <button class="btn btn-cancel" type="button" @click="$emit('close')">Отмена</button>
    </template>
  </BaseDialog>
</template>

<style lang="scss" scoped>
.guest-select-wrapper {
  position: relative;
}
</style>
