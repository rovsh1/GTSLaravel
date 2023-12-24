<script setup lang="ts">

import { computed, nextTick, ref, watch, watchEffect } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { isDataValid } from '~resources/composables/form'
import { CarFormData } from '~resources/views/booking/shared/lib/data-types'

import { Car } from '~api/supplier/cars'

import BaseDialog from '~components/BaseDialog.vue'
import { SelectOption } from '~components/Bootstrap/lib'
import SelectComponent from '~components/SelectComponent.vue'

const props = withDefaults(defineProps<{
  opened: MaybeRef<boolean>
  isFetching: MaybeRef<boolean>
  availableCars: Car[]
  formData: Partial<CarFormData>
  titleText?: string
  inputSelectText?: string
}>(), {
  titleText: 'Данные автомобиля',
  inputSelectText: 'Автомобиль',
})

const emit = defineEmits<{
  (event: 'close'): void
  (event: 'clear'): void
  (event: 'submit', payload: any): void
}>()

const setFormData = () => ({
  id: props.formData.id,
  ...props.formData,
})

const formData = ref<Partial<CarFormData>>(setFormData())

const selectedCar = ref<Car>()

const isSubmiting = computed(() => Boolean(props.isFetching))

const setSelectedCar = (carId: number | undefined) => {
  if (carId !== undefined) {
    selectedCar.value = props.availableCars.find((car) => car.id === carId)
    formData.value.carsCount = 1
  } else {
    selectedCar.value = undefined
    formData.value.carsCount = undefined
  }
  formData.value.baggageCount = undefined
  formData.value.passengersCount = undefined
}

watchEffect(() => {
  setSelectedCar(props.formData.carId)
  formData.value = setFormData()
})

const carsCountValidate = (): boolean => ((formData.value.carsCount !== undefined && formData.value.carsCount !== null
  && formData.value?.carsCount > 0))

const validateCreateCarForm = computed(() => (isDataValid(null, formData.value.carId)
  && isDataValid(null, formData.value.passengersCount) && isDataValid(null, formData.value.carsCount, carsCountValidate())))

const submitDisable = computed(() => !validateCreateCarForm.value)

const onModalSubmit = async () => {
  if (!validateCreateCarForm.value) {
    return
  }
  const payload = {
    ...formData.value,
  }
  emit('submit', payload)
}

const carsOptions = computed<SelectOption[]>(
  () => props.availableCars?.map((car: Car) => ({ value: car.id, label: `${car.mark} ${car.model}` })) || [],
)

const passengersOptions = computed<SelectOption[]>(
  () => Array.from({ length: (selectedCar.value?.passengersNumber || 0) }, (_, index) => index + 1)
    .map((value) => ({ value, label: value.toString() })) || [],
)

const baggageOptions = computed<SelectOption[]>(
  () => Array.from({ length: (selectedCar.value?.bagsNumber || 0) }, (_, index) => index + 1)
    .map((value) => ({ value, label: value.toString() })) || [],
)

const resetForm = () => {
  emit('clear')
  formData.value.carId = undefined
  formData.value.carsCount = undefined
  formData.value.passengersCount = undefined
  formData.value.baggageCount = undefined
  nextTick(() => {
    $('.is-invalid').removeClass('is-invalid')
  })
}

const closeModal = () => {
  resetForm()
  emit('close')
  emit('clear')
}

watch(() => props.opened, () => {
  if (!props.opened) {
    resetForm()
  }
})

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
      <div class="col-md-12">
        <SelectComponent
          :options="carsOptions"
          label="Модель автомобиля"
          required
          :value="formData.carId"
          @change="(value, event) => {
            formData.carId = value ? Number(value) : value
            isDataValid(event, value)
            setSelectedCar(formData.carId)
          }"
        />
      </div>
      <div class="col-md-12">
        <div class="field-required">
          <label for="cars_count">Количествово автомобилей</label>
          <input
            id="cars_count"
            v-model="formData.carsCount"
            :disabled="!formData.carId"
            class="form-control"
            type="number"
            required
            @input="isDataValid($event.target, formData.carsCount, carsCountValidate())"
            @blur="isDataValid($event.target, formData.carsCount, carsCountValidate())"
          >
        </div>
      </div>
      <div class="col-md-12">
        <SelectComponent
          :options="passengersOptions"
          label="Количествово пассажиров"
          :disabled="!formData.carId"
          required
          :value="formData.passengersCount"
          @change="(value, event) => {
            formData.passengersCount = value ? Number(value) : value
            isDataValid(event, value)
          }"
        />
      </div>
      <div class="col-md-12">
        <SelectComponent
          :options="baggageOptions"
          label="Количествово багажа"
          :disabled="!formData.carId"
          :value="formData.baggageCount || undefined"
          :allow-empty-item="true"
          empty-item="Нет"
          @change="(value, event) => {
            formData.passengersCount = value ? Number(value) : value
            formData.baggageCount = value ? Number(value) : value
          }"
        />
      </div>
    </form>
    <template v-if="formData.id === undefined" #actions-start>
      <button class="btn btn-default" type="button" :disabled="isSubmiting" @click="resetForm">
        Сбросить
      </button>
    </template>
    <template #actions-end>
      <button class="btn btn-primary" type="button" :disabled="submitDisable || isSubmiting" @click="onModalSubmit">
        Сохранить
      </button>
      <button class="btn btn-cancel" type="button" :disabled="isSubmiting" @click="$emit('close')">Отмена</button>
    </template>
  </BaseDialog>
</template>

<style lang="scss" scoped>
.guest-select-wrapper {
  position: relative;
}
</style>
