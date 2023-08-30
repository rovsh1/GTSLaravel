<script setup lang="ts">
import { ref } from 'vue'

import { MaybeRef } from '@vueuse/core'

import { CountryResponse } from '~api/country'

import BaseDialog from '~components/BaseDialog.vue'

defineProps<{
  opened: MaybeRef<boolean>
  countries: CountryResponse[]
}>()

const emit = defineEmits<{
  (event: 'close'): void
  (event: 'submit'): void
}>()

const modalForm = ref<HTMLFormElement>()
const isFetching = ref(false)

const onModalSubmit = async () => {
}

const closeModal = () => {
  modalForm.value?.reset()
  emit('close')
}

</script>

<template>
  <BaseDialog
    :opened="opened as boolean"
    :loading="isFetching"
    @close="closeModal"
    @keydown.enter="onModalSubmit"
  >
    Модалка
    <!--    <template #title>Данные гостя</template>-->

    <!--    <form ref="modalForm" class="row g-3">-->
    <!--      <div class="col-md-12">-->
    <!--        <BootstrapSelectBase-->
    <!--          id="nationality_id"-->
    <!--          :options="countryOptions"-->
    <!--          label="Гражданство"-->
    <!--          :value="formData.countryId as number"-->
    <!--          required-->
    <!--          @input="value => formData.countryId = value as number"-->
    <!--        />-->
    <!--      </div>-->
    <!--      <div class="col-md-12">-->
    <!--        <div class="field-required">-->
    <!--          <label for="full_name">ФИО</label>-->
    <!--              <input -->
    <!--                id="full_name" -->
    <!--                v-model="formData.fullName" -->
    <!--                class="form-control" -->
    <!--                required-->
    <!--              >-->
    <!--        </div>-->
    <!--      </div>-->
    <!--      <div class="col-md-12">-->
    <!--        <BootstrapSelectBase-->
    <!--          id="gender"-->
    <!--          :options="genderOptions"-->
    <!--          label="Пол"-->
    <!--          :value="formData.gender as number"-->
    <!--          required-->
    <!--          @input="value => formData.gender = value as number"-->
    <!--        />-->
    <!--      </div>-->
    <!--      <div class="col-md-12">-->
    <!--        <BootstrapSelectBase-->
    <!--          id="age_type"-->
    <!--          :options="ageTypeOptions"-->
    <!--          label="Тип"-->
    <!--          :value="ageType"-->
    <!--          @input="value => handleChangeAgeType(Number(value))"-->
    <!--        />-->
    <!--      </div>-->

    <!--      <div v-if="!formData.isAdult" class="col-md-12">-->
    <!--        <div class="field-required">-->
    <!--          <label for="child_age">Возраст</label>-->
    <!--          <input-->
    <!--            id="child_age"-->
    <!--            v-model="formData.age"-->
    <!--            type="number"-->
    <!--            class="form-control"-->
    <!--            autocomplete="off"-->
    <!--            required-->
    <!--            min="0"-->
    <!--            max="18"-->
    <!--          >-->
    <!--        </div>-->
    <!--      </div>-->
    <!--    </form>-->

    <template #actions-end>
      <button class="btn btn-primary" type="button" @click="onModalSubmit">Сохранить</button>
      <button class="btn btn-cancel" type="button" @click="$emit('close')">Отмена</button>
    </template>
  </BaseDialog>
</template>
