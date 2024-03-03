<script setup lang="ts">

import { computed, nextTick, ref, watch, watchEffect } from 'vue'

import { MaybeRef } from '@vueuse/core'
import BaseDialog from 'gts-components/Base/BaseDialog'
import SelectComponent from 'gts-components/Base/SelectComponent'
import { SelectOption } from 'gts-components/Bootstrap/lib'

import { genderOptions } from '~resources/views/booking/shared/lib/constants'
import { GuestFormData } from '~resources/views/booking/shared/lib/data-types'

import { CountryResponse } from '~api/country'
import { Guest } from '~api/order/guest'

import { isDataValid } from '~helpers/form'

const props = withDefaults(defineProps<{
  opened: MaybeRef<boolean>
  isFetching: MaybeRef<boolean>
  countries: CountryResponse[]
  formData: Partial<GuestFormData>
  orderGuests?: Guest[] | null
  titleText?: string
  inputSelectText?: string
}>(), {
  titleText: 'Данные гостя',
  inputSelectText: 'Гость',
  orderGuests: null,
})

const emit = defineEmits<{
  (event: 'close'): void
  (event: 'clear'): void
  (event: 'submit', payload: any): void
}>()

const ageTypeOptions: SelectOption[] = [
  { value: 0, label: 'Взрослый' },
  { value: 1, label: 'Ребенок' },
]

const setFormData = () => ({
  id: props.formData.id,
  ...props.formData,
  selectedGuestFromOrder: undefined,
})

const formDataLocale = ref<Partial<GuestFormData>>(setFormData())

const isSubmiting = computed(() => Boolean(props.isFetching))

const localAgeType = ref<number>()
const ageType = computed<number>({
  get: () => {
    if (localAgeType.value !== undefined) {
      return localAgeType.value
    }

    return formDataLocale.value.isAdult ? 0 : 1
  },
  set: (type: number): void => {
    localAgeType.value = type
  },
})

const handleChangeAgeType = (type: number): void => {
  if (!isNaN(type)) {
    ageType.value = type
    formDataLocale.value.isAdult = type === 0
    formDataLocale.value.age = null
  }
  nextTick(() => {
    $('#child_age').removeClass('is-invalid')
  })
}

watchEffect(() => {
  formDataLocale.value = setFormData()
  localAgeType.value = undefined
})

const ageValidate = (): boolean => ((formDataLocale.value.age !== undefined && formDataLocale.value.age !== null
  && (formDataLocale.value?.age >= 0 && formDataLocale.value?.age <= 18)))

const validateCreateGuestForm = computed(() => (isDataValid(null, formDataLocale.value.countryId)
  && isDataValid(null, formDataLocale.value.fullName) && isDataValid(null, formDataLocale.value.gender)
  && (!!(ageType.value === 1 ? isDataValid(null, formDataLocale.value.age, ageValidate()) : true))))

const validateSelectGuestForm = computed(() => (isDataValid(null, formDataLocale.value.selectedGuestFromOrder)))

const isFormValid = (): boolean => {
  if (
    ((validateCreateGuestForm.value || validateSelectGuestForm.value) && formDataLocale.value.id === undefined)
    || (formDataLocale.value.id !== undefined && validateCreateGuestForm.value)
  ) {
    return true
  }
  return false
}

const submitDisable = computed(() => !isFormValid())

const onModalSubmit = async () => {
  if (!isFormValid() || submitDisable.value || isSubmiting.value) {
    return
  }
  if (formDataLocale.value.id !== undefined) {
    const payload = {
      guestId: formDataLocale.value.id,
      ...formDataLocale.value,
    }
    emit('submit', payload)
  } else if (formDataLocale.value.selectedGuestFromOrder !== undefined) {
    const payload = {
      id: formDataLocale.value.selectedGuestFromOrder,
    }
    emit('submit', payload)
  } else if (formDataLocale.value.selectedGuestFromOrder === undefined) {
    const payload = {
      ...formDataLocale.value,
    }
    emit('submit', payload)
  }
  localAgeType.value = undefined
}

const countryOptions = computed<SelectOption[]>(
  () => props.countries?.map((country: CountryResponse) => ({ value: country.id, label: country.name })) || [],
)

const guestsOptions = computed<SelectOption[]>(
  () => props.orderGuests?.map((guest: Guest) => ({ value: guest.id, label: guest.fullName })) || [],
)

const resetForm = () => {
  emit('clear')
  formDataLocale.value.countryId = undefined
  formDataLocale.value.fullName = ''
  formDataLocale.value.gender = undefined
  formDataLocale.value.age = undefined
  formDataLocale.value.selectedGuestFromOrder = undefined
  ageType.value = 0
  handleChangeAgeType(ageType.value)
  nextTick(() => {
    $('.is-invalid').removeClass('is-invalid')
  })
}

const closeModal = () => {
  ageType.value = 0
  handleChangeAgeType(ageType.value)
  resetForm()
  emit('close')
  emit('clear')
}

const onChangeSelectGuest = (value: any) => {
  formDataLocale.value.selectedGuestFromOrder = value ? Number(value) : undefined
  const getCurrentGuestData = props.orderGuests?.filter((guest: Guest) =>
    guest.id === formDataLocale.value.selectedGuestFromOrder)
  if (getCurrentGuestData && getCurrentGuestData.length > 0) {
    const [firstGuest] = getCurrentGuestData
    formDataLocale.value.fullName = firstGuest.fullName
    formDataLocale.value.age = firstGuest.age
    formDataLocale.value.countryId = firstGuest.countryId
    formDataLocale.value.gender = firstGuest.gender
    formDataLocale.value.isAdult = firstGuest.isAdult
    localAgeType.value = undefined
  } else {
    resetForm()
  }
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
    @keydown.enter="onModalSubmit"
    @close="closeModal"
  >
    <template #title>{{ titleText }}</template>
    <form class="row g-3">
      <div
        v-if="orderGuests && guestsOptions.length > 0 && formDataLocale.id === undefined"
        class="col-md-12 guest-select-wrapper"
      >
        <SelectComponent
          v-if="opened"
          :options="guestsOptions"
          :label="inputSelectText"
          :value="formDataLocale.selectedGuestFromOrder"
          :allow-empty-item="true"
          empty-item="Создать нового гостя"
          @change="(value) => {
            onChangeSelectGuest(value)
          }"
        />
      </div>
      <div class="col-md-12">
        <SelectComponent
          v-if="opened"
          :options="countryOptions"
          label="Гражданство"
          :disabled="!!formDataLocale.selectedGuestFromOrder"
          :value="formDataLocale.countryId"
          required
          @change="(value, event) => {
            formDataLocale.countryId = value ? Number(value) : value
            isDataValid(event, value)
          }"
        />
      </div>
      <div class="col-md-12">
        <div class="field-required">
          <label for="full_name">ФИО</label>
          <input
            id="full_name"
            v-model="formDataLocale.fullName"
            :disabled="!!formDataLocale.selectedGuestFromOrder"
            class="form-control"
            required
            @input="isDataValid($event.target, formDataLocale.fullName)"
            @blur="isDataValid($event.target, formDataLocale.fullName)"
          >
        </div>
      </div>
      <div class="col-md-12">
        <SelectComponent
          v-if="opened"
          :options="genderOptions"
          label="Пол"
          :disabled="!!formDataLocale.selectedGuestFromOrder"
          :value="formDataLocale.gender"
          required
          @change="(value, event) => {
            formDataLocale.gender = value ? Number(value) : value
            isDataValid(event, value)
          }"
        />
      </div>
      <div class="col-md-12">
        <SelectComponent
          v-if="opened"
          :options="ageTypeOptions"
          label="Тип"
          :disabled="!!formDataLocale.selectedGuestFromOrder"
          :value="ageType"
          @change="(value, event) => {
            handleChangeAgeType(Number(value))
            isDataValid(event, value)
          }"
        />
      </div>

      <div class="col-md-12">
        <div :class="[ageType === 1 ? 'field-required' : '']">
          <label for="child_age">Возраст</label>
          <input
            id="child_age"
            v-model="formDataLocale.age"
            :disabled="!!formDataLocale.selectedGuestFromOrder || formDataLocale.isAdult || isNaN(ageType)"
            type="number"
            class="form-control"
            autocomplete="off"
            :required="formDataLocale.isAdult === false"
            min="0"
            max="18"
            @input="isDataValid($event.target, formDataLocale.age, ageValidate())"
            @blur="isDataValid($event.target, formDataLocale.age, ageValidate())"
          >
        </div>
      </div>
    </form>
    <template v-if="formDataLocale.id === undefined" #actions-start>
      <button class="btn btn-default" type="button" :disabled="isSubmiting" @click="resetForm">
        Сбросить
      </button>
    </template>
    <template #actions-end>
      <button
        class="btn btn-primary"
        type="button"
        :disabled="submitDisable || isSubmiting"
        @click="onModalSubmit"
      >
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
