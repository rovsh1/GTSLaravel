<script setup lang="ts">

import { computed, ref } from 'vue'

import { useToggle } from '@vueuse/core'
import { Tab } from 'bootstrap'

import { useCityStore } from '~resources/store/city'
import { useCurrencyStore } from '~resources/store/currency'
import ManagerSelect from '~resources/views/hotel-booking/form/components/ManagerSelect.vue'
import {
  clientTypeOptions,
  genderOptions,
  mapEntitiesToSelectOptions, residentTypeOptions,
} from '~resources/views/hotel-booking/show/lib/constants'

import { useApplicationEventBus } from '~lib/event-bus'

import BaseDialog from '~components/BaseDialog.vue'
import BootstrapSelectBase from '~components/Bootstrap/BootstrapSelectBase.vue'
import IconButton from '~components/IconButton.vue'

const statusOptions = mapEntitiesToSelectOptions([
  { id: 1, name: 'Активный' },
  { id: 2, name: 'Заблокирован' },
  { id: 3, name: 'Архив' },
])

const legalIndustryOptions = mapEntitiesToSelectOptions([
  { id: 1, name: 'Турагенство' },
  { id: 2, name: 'Туристическая компания' },
])

const legalTypeOptions = mapEntitiesToSelectOptions([
  { id: 1, name: 'OTA' },
  { id: 2, name: 'TA' },
  { id: 3, name: 'TO' },
])

const { cities } = useCityStore()
const cityOptions = computed(() => (cities ? mapEntitiesToSelectOptions(cities) : []))

const { currencies } = useCurrencyStore()
const currencyOptions = computed(() => (currencies ? mapEntitiesToSelectOptions(currencies) : []))

const [isOpened, toggleModal] = useToggle()
const name = ref('')
const type = ref(1)
const cityId = ref()
const status = ref(1)
const currency = ref()
const managerId = ref()
const priceTypes = ref()

const physicalGender = ref()

const legalName = ref('')
const legalType = ref()
const legalIndustry = ref()
const legalAddress = ref('')
const legalBik = ref('')
const legalBankCity = ref('')
const legalInn = ref('')
const legalOkpoCode = ref('')
const legalCorrAccount = ref('')
const legalKpp = ref('')
const legalBankName = ref('')
const legalCurrentAccount = ref('')

const eventBus = useApplicationEventBus()

const isFirstStepValid = ref(false)
const clientGeneralForm = ref<HTMLFormElement>()
const onModalSubmit = async () => {
  if (!isFirstStepValid.value && !clientGeneralForm.value?.reportValidity()) {
    return
  }
  isFirstStepValid.value = true

  const tab = ref()
  if (type.value === 1) {
    tab.value = Tab.getOrCreateInstance('a[data-bs-target="#physical-details"]')
  } else {
    tab.value = Tab.getOrCreateInstance('a[data-bs-target="#legal-details"]')
  }
  // @todo это сейчас не работает, возможно нужно сделать компонент stepper/wizard
  console.log(tab.value)
  tab.value.show()

  // if (type.value !== 1 && isEmpty(legalType) && isEmpty(legalAddress)) {
  //   alert('Не заполнены обязательные поля юр. лица')
  //   return
  // }
  // alert('success')
  // toggleModal(false)
  const clientId = 123
  eventBus.emit('client-created', { clientId })
}

</script>

<template>
  <BaseDialog
    :opened="isOpened as boolean"
    @close="toggleModal(false)"
    @submit="onModalSubmit"
  >
    <template #title>Создать клиента</template>

    <div class="mb-4">
      <ul class="nav nav-underline border-bottom" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link active" data-bs-target="#main" data-bs-toggle="tab" type="button" role="tab">
            1. Основные данные
          </a>
        </li>
        <li v-if="type === 1" class="nav-item" role="presentation" disabled>
          <a
            class="nav-link"
            aria-disabled="true"
            data-bs-target="#physical-details"
            data-bs-toggle="tab"
            role="tab"
          >
            2. Данные физ. лица
          </a>
        </li>
        <li v-else class="nav-item" role="presentation">
          <a
            class="nav-link has-required-fields"
            aria-disabled="true"
            data-bs-target="#legal-details"
            data-bs-toggle="tab"
            role="tab"
          >
            2. Данные юр. лица
          </a>
        </li>
      </ul>
    </div>

    <div id="main" role="tabpanel" class="tab-pane fade active show row g-3">
      <form ref="clientGeneralForm" class="tab-content">
        <div class="col-md-12">
          <div class="field-required">
            <label for="name">ФИО или название компании</label>
            <input id="name" v-model="name" class="form-control" required>
          </div>
        </div>

        <div class="col-md-12">
          <BootstrapSelectBase
            id="type"
            label="Тип"
            :options="clientTypeOptions"
            :value="type"
            required
            @input="value => type = Number(value)"
          />
        </div>

        <div class="col-md-12">
          <BootstrapSelectBase
            id="city-id"
            label="Город"
            :options="cityOptions"
            :value="cityId"
            required
            @input="value => cityId = Number(value)"
          />
        </div>

        <div class="col-md-12">
          <BootstrapSelectBase
            id="status"
            label="Статус"
            :options="statusOptions"
            :value="status"
            :show-empty-item="false"
            @input="value => status = Number(value)"
          />
        </div>

        <div class="col-md-12">
          <BootstrapSelectBase
            id="currency"
            label="Валюта"
            :options="currencyOptions"
            :value="currency"
            required
            @input="value => currency = Number(value)"
          />
        </div>

        <div class="col-md-12">
          <BootstrapSelectBase
            id="price-types"
            label="Тариф"
            :options="residentTypeOptions"
            :value="priceTypes"
            multiple
            required
            @input="value => priceTypes = value"
          />
        </div>

        <div class="col-md-12">
          <ManagerSelect
            :value="managerId"
            parent=".dialog"
            @input="value => managerId = value"
          />
        </div>
      </form>
    </div>

    <div id="physical-details" role="tabpanel" class="tab-pane fade row g-3">
      <form ref="clientPhysicalForm" class="tab-content">
        <div class="col-md-12">
          <BootstrapSelectBase
            id="gender"
            label="Пол"
            :options="genderOptions"
            :value="physicalGender"
            @input="value => physicalGender = Number(value)"
          />
        </div>
      </form>
    </div>

    <div id="legal-details" role="tabpanel" class="tab-pane fade row g-3">
      <form ref="clientLegalForm" class="tab-content">
        <div class="col-md-12">
          <label for="legal-name">Наименование</label>
          <input id="legal-name" v-model="legalName" class="form-control">
        </div>

        <div class="col-md-12">
          <BootstrapSelectBase
            id="industry"
            label="Индустрия"
            :options="legalIndustryOptions"
            :value="legalIndustry"
            @input="value => legalIndustry = Number(value)"
          />
        </div>

        <div class="col-md-12">
          <BootstrapSelectBase
            id="legal-type"
            label="Тип"
            :options="legalTypeOptions"
            :value="legalType"
            required
            @input="value => legalType = Number(value)"
          />
        </div>

        <div class="col-md-12 pb-3 border-bottom">
          <div class="field-required">
            <label for="legal-address">Адрес</label>
            <input id="legal-address" v-model="legalAddress" class="form-control" required>
          </div>
        </div>

        <div class="col-md-12">
          <div>
            <label for="legal-bik">БИК</label>
            <input id="legal-bik" v-model="legalBik" class="form-control">
          </div>
        </div>

        <div class="col-md-12">
          <div>
            <label for="legal-bank-city">Город банка</label>
            <input id="legal-bank-city" v-model="legalBankCity" class="form-control">
          </div>
        </div>

        <div class="col-md-12">
          <div>
            <label for="legal-inn">ИНН</label>
            <input id="legal-inn" v-model="legalInn" class="form-control">
          </div>
        </div>

        <div class="col-md-12">
          <div>
            <label for="legal-okpo-code">Код ОКПО</label>
            <input id="legal-okpo-code" v-model="legalOkpoCode" class="form-control">
          </div>
        </div>

        <div class="col-md-12">
          <div>
            <label for="legal-corr-acc">Корреспондентский счет</label>
            <input id="legal-corr-acc" v-model="legalCorrAccount" class="form-control">
          </div>
        </div>

        <div class="col-md-12">
          <div>
            <label for="legal-kpp">КПП</label>
            <input id="legal-kpp" v-model="legalKpp" class="form-control">
          </div>
        </div>

        <div class="col-md-12">
          <div>
            <label for="legal-bank-name">Наименование банка</label>
            <input id="legal-bank-name" v-model="legalBankName" class="form-control">
          </div>
        </div>

        <div class="col-md-12">
          <div>
            <label for="legal-current-acc">Рассчетный счет</label>
            <input id="legal-current-acc" v-model="legalCurrentAccount" class="form-control">
          </div>
        </div>
      </form>
    </div>

    <template #actions-end>
      <button class="btn btn-primary" type="button" @click="onModalSubmit">Сохранить</button>
      <button class="btn btn-cancel" type="button" @click="toggleModal(false)">Отмена</button>
    </template>
  </BaseDialog>

  <IconButton class="btn btn-add" icon="add" @click="toggleModal">
    Создать
  </IconButton>
</template>

<style scoped lang="scss">
a.has-required-fields::after {
  content: " *";
  color: #DC493A;
}
</style>
