<script setup lang="ts">

import { computed, nextTick, reactive, ref } from 'vue'

import { MaybeRef } from '@vueuse/core'
import { z } from 'zod'

import { isDataValid } from '~resources/composables/form'
import { genderOptions } from '~resources/views/hotel-booking/show/lib/constants'
import { GuestFormData } from '~resources/views/hotel-booking/show/lib/data-types'
import { useOrderStore } from '~resources/views/hotel-booking/show/store/order-currency'

import { CountryResponse } from '~api/country'

import { requestInitialData } from '~lib/initial-data'

import BaseDialog from '~components/BaseDialog.vue'
import BootstrapSelectBase from '~components/Bootstrap/BootstrapSelectBase.vue'
import BootstrapTabs from '~components/Bootstrap/BootstrapTabs/BootstrapTabs.vue'
import BootstrapTabsLink from '~components/Bootstrap/BootstrapTabs/components/BootstrapTabsLink.vue'
import BootstrapTabsTabContent from '~components/Bootstrap/BootstrapTabs/components/BootstrapTabsTabContent.vue'
import { SelectOption } from '~components/Bootstrap/lib'
import Select2BaseSelect from '~components/Select2BaseSelect.vue'

const props = withDefaults(defineProps<{
  opened: MaybeRef<boolean>
  roomBookingId: MaybeRef<number>
  countries: CountryResponse[]
  formData: Partial<GuestFormData>
  dataForSelectTab?: SelectOption[] | null
  titleText?: string
  tabCreateText?: string
  tabSelectText?: string
  inputSelectText?: string
  guestId?: number
}>(), {
  titleText: 'Данные гостя',
  tabCreateText: 'Создать гостя',
  tabSelectText: 'Выбрать гостя из заказа',
  inputSelectText: 'Гость',
  guestId: undefined,
  dataForSelectTab: null,
})

const emit = defineEmits<{
  (event: 'close'): void
  (event: 'submit'): void
}>()

const { bookingID } = requestInitialData(
  'view-initial-data-hotel-booking',
  z.object({
    bookingID: z.number(),
  }),
)

/* tabs */
const tabCreateActive = ref<boolean>(true)
const tabSelectActive = ref<boolean>(false)

const guestSelect = ref()

const orderStore = useOrderStore()
const orderId = computed(() => orderStore.order.id)

const ageTypeOptions: SelectOption[] = [
  { value: 0, label: 'Взрослый' },
  { value: 1, label: 'Ребенок' },
]

const localAgeType = ref<number>()
const ageType = computed<number>({
  get: () => {
    if (localAgeType.value !== undefined) {
      return localAgeType.value
    }

    return props.formData.isAdult ? 0 : 1
  },
  set: (type: number): void => {
    localAgeType.value = type
  },
})

const formData = reactive<GuestFormData>({
  bookingID,
  id: props.guestId,
  ...props.formData,
})

const isFetching = ref<boolean>(false)

const validateCreateGuestForm = computed(() => (isDataValid(null, formData.countryId)
&& isDataValid(null, formData.fullName) && isDataValid(null, formData.gender)
&& (ageType.value === 1 ? isDataValid(null, formData.age) : true)))

const validateSelectGuestForm = computed(() => (isDataValid(null, formData.selectedGuestFromOrder)))

const modalForm = ref<HTMLFormElement>()
const onModalSubmit = async () => {
  /* if (!validateForm<GuestFormData>(modalForm as Ref<HTMLFormElement>, formData)) {
    return
  }
  isFetching.value = true
  formData.value.roomBookingId = unref<number>(props.roomBookingId)
  formData.value.orderId = orderId.value
  if (formData.value.id !== undefined) {
    const payload = {
      guestId: formData.value.id,
      ...formData.value,
    }
    await updateOrderGuest(payload)
  } else {
    const payload = {
      hotelBookingRoomId: formData.value.roomBookingId,
      hotelBookingId: bookingID,
      ...formData.value,
    }
    await addOrderGuest(payload)
    // @todo добавить возможность выбрать из списка туристов заказа,
  }
  localAgeType.value = undefined
  isFetching.value = false
  emit('submit') */
}

const countryOptions = computed<SelectOption[]>(
  () => props.countries?.map((country: CountryResponse) => ({ value: country.id, label: country.name })) || [],
)

const handleChangeAgeType = (type: number): void => {
  ageType.value = type
  formData.isAdult = type === 0
  formData.age = null
}

const closeModal = () => {
  modalForm.value?.reset()
  emit('close')
}

const switchTab = () => {
  tabCreateActive.value = !tabCreateActive.value
  tabSelectActive.value = !tabSelectActive.value
}

const resetForm = () => {
  formData.countryId = undefined
  formData.fullName = ''
  formData.gender = undefined
  formData.age = undefined
  formData.selectedGuestFromOrder = undefined
  ageType.value = 0
  handleChangeAgeType(ageType.value)
  guestSelect.value.clearComponentValue()
  nextTick(() => {
    $('.is-invalid').removeClass('is-invalid')
  })
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
    <template v-if="!dataForSelectTab">
      <form ref="modalForm" class="row g-3">
        <div class="col-md-12">
          <BootstrapSelectBase
            id="nationality_id"
            :options="countryOptions"
            label="Гражданство"
            :value="formData.countryId as number"
            required
            @input="(value: any) => formData.countryId = value as number"
          />
        </div>
        <div class="col-md-12">
          <div class="field-required">
            <label for="full_name">ФИО</label>
            <input id="full_name" v-model="formData.fullName" class="form-control" required>
          </div>
        </div>
        <div class="col-md-12">
          <BootstrapSelectBase
            id="gender"
            :options="genderOptions"
            label="Пол"
            :value="formData.gender as number"
            required
            @input="(value: any) => formData.gender = value as number"
          />
        </div>
        <div class="col-md-12">
          <BootstrapSelectBase
            id="age_type"
            :options="ageTypeOptions"
            label="Тип"
            :value="ageType"
            @input="(value: any) => handleChangeAgeType(Number(value))"
          />
        </div>

        <div v-if="!formData.isAdult" class="col-md-12">
          <div class="field-required">
            <label for="child_age">Возраст</label>
            <input
              id="child_age"
              v-model="formData.age"
              type="number"
              class="form-control"
              autocomplete="off"
              required
              min="0"
              max="18"
            >
          </div>
        </div>
      </form>
    </template>
    <BootstrapTabs v-else>
      <template #links>
        <BootstrapTabsLink
          tab-name="create-new-guest"
          :is-active="tabCreateActive"
          :is-required="true"
          :is-disabled="false"
          @click.prevent="switchTab"
        >
          {{ tabCreateText }}
        </BootstrapTabsLink>
        <BootstrapTabsLink
          tab-name="select-exists-guest"
          :is-active="tabSelectActive"
          :is-required="true"
          :is-disabled="false"
          @click.prevent="switchTab"
        >
          {{ tabSelectText }}
        </BootstrapTabsLink>
      </template>
      <template #tabs>
        <BootstrapTabsTabContent tab-name="select-exists-guest" :is-active="tabSelectActive">
          <div class="guest-select-wrapper">
            <Select2BaseSelect
              id="guest-select"
              ref="guestSelect"
              :label="inputSelectText"
              :options="[]"
              :value="formData.selectedGuestFromOrder"
              parent=".guest-select-wrapper"
              :enable-tags="false"
              required
              :show-empty-item="false"
              @blur="isDataValid($event, formData.selectedGuestFromOrder)"
              @input="(value: any) => formData.selectedGuestFromOrder = Number(value)"
            />
          </div>
        </BootstrapTabsTabContent>
        <BootstrapTabsTabContent tab-name="create-new-guest" :is-active="tabCreateActive">
          <form ref="modalForm" class="row g-3">
            <div class="col-md-12">
              <BootstrapSelectBase
                id="nationality_id"
                :options="countryOptions"
                label="Гражданство"
                :value="formData.countryId as number"
                required
                @blur="isDataValid($event, formData.countryId)"
                @input="(value: any) => formData.countryId = value as number"
              />
            </div>
            <div class="col-md-12">
              <div class="field-required">
                <label for="full_name">ФИО</label>
                <input
                  id="full_name"
                  v-model="formData.fullName"
                  class="form-control"
                  required
                  @blur="isDataValid($event, formData.fullName)"
                >
              </div>
            </div>
            <div class="col-md-12">
              <BootstrapSelectBase
                id="gender"
                :options="genderOptions"
                label="Пол"
                :value="formData.gender as number"
                required
                @blur="isDataValid($event, formData.gender)"
                @input="(value: any) => formData.gender = value as number"
              />
            </div>
            <div class="col-md-12">
              <BootstrapSelectBase
                id="age_type"
                :options="ageTypeOptions"
                label="Тип"
                :value="ageType"
                @input="(value: any) => handleChangeAgeType(Number(value))"
              />
            </div>

            <div v-if="!formData.isAdult" class="col-md-12">
              <div class="field-required">
                <label for="child_age">Возраст</label>
                <input
                  id="child_age"
                  v-model="formData.age"
                  type="number"
                  class="form-control"
                  autocomplete="off"
                  required
                  min="0"
                  max="18"
                  @blur="isDataValid($event, formData.age)"
                >
              </div>
            </div>
          </form>
        </BootstrapTabsTabContent>
      </template>
    </BootstrapTabs>
    <template #actions-start>
      <button class="btn btn-default" type="button" @click="resetForm">
        Сбросить
      </button>
    </template>
    <template #actions-end>
      <button
        class="btn btn-primary"
        type="button"
        :disabled="(tabCreateActive && !validateCreateGuestForm) || (tabSelectActive && !validateSelectGuestForm)"
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
