<script lang="ts" setup>

import { computed, onMounted, reactive, ref } from 'vue'

import { useDebounceFn, useToggle } from '@vueuse/core'

import { useUpdateLocaleDictionary } from '~resources/api/locale-dictionary/update'

import { LocaleDictionary, useLocaleDictionaryAPI } from '~api/locale-dictionary/search'

import BaseDialog from '~components/BaseDialog.vue'
import Card from '~components/Bootstrap/BootstrapCard/BootstrapCard.vue'
import BootstrapTabs from '~components/Bootstrap/BootstrapTabs/BootstrapTabs.vue'
import BootstrapTabsLink from '~components/Bootstrap/BootstrapTabs/components/BootstrapTabsLink.vue'
import BootstrapTabsTabContent from '~components/Bootstrap/BootstrapTabs/components/BootstrapTabsTabContent.vue'
import OverlayLoading from '~components/OverlayLoading.vue'

type LocaleDictionaryForm = LocaleDictionary & {
  activeLocale: string
}

const [isOpened, toggleModal] = useToggle()

const searchInput = ref<HTMLElement | null>(null)

const searchQuery = ref<string>('')

const editableLocale = reactive<LocaleDictionaryForm>({
  key: '',
  valueEn: '',
  valueRu: '',
  valueUz: '',
  activeLocale: 'ru',
})

const { isFetching: isLocalesFetching, data: locales,
  execute: fetchLocales } = useLocaleDictionaryAPI(computed(() => {
  const searchQueryProp = searchQuery.value
  return {
    searchQuery: searchQueryProp,
  }
}))

const {
  execute: executeUpdateLocaleDictionary,
  isFetching: isUpdateLocaleDictionary,
} = useUpdateLocaleDictionary(computed(() => {
  const { key, valueEn, valueRu, valueUz } = editableLocale
  return {
    key,
    valueEn,
    valueRu,
    valueUz,
  }
}))

const setUrlHash = (value: string) => {
  window.location.hash = value.trim() || ''
}

const handleSearchInput = useDebounceFn(async (event: Event) => {
  const inputElement = event.target as HTMLInputElement
  const { value } = inputElement
  searchQuery.value = value.trim()
  setUrlHash(searchQuery.value)
  await fetchLocales()
}, 500)

const setEditableData = (key: string, data: LocaleDictionary) => {
  editableLocale.activeLocale = key
  editableLocale.key = data.key
  editableLocale.valueEn = data.valueEn
  editableLocale.valueRu = data.valueRu
  editableLocale.valueUz = data.valueUz
  toggleModal(true)
}

const setActiveTab = (key: string) => {
  editableLocale.activeLocale = key
}

const handleSubmitEditableLocale = async () => {
  await executeUpdateLocaleDictionary()
  toggleModal(false)
  await fetchLocales()
}

onMounted(async () => {
  let { hash } = window.location
  if (hash.charAt(0) === '#') {
    hash = hash.slice(1)
  }
  if (hash !== '') {
    setUrlHash(hash)
  }
  searchQuery.value = hash
  searchInput.value?.focus()
  await fetchLocales()
})
</script>
<template>
  <Card>
    <div>
      <div class="quicksearch-wrapper mt-3">
        <input
          ref="searchInput"
          type="search"
          class="form-control"
          placeholder="Поиск"
          autocomplete="off"
          :value="searchQuery"
          @input="handleSearchInput"
        />
        <button class="icon" type="submit" title="Найти">search</button>
      </div>
      <div class="position-relative">
        <OverlayLoading v-if="isLocalesFetching" />
        <table v-if="locales && locales.length" class="table table-striped mt-3">
          <tbody>
            <tr v-for="locale in locales" :key="locale.key">
              <td class="column-text column-source translate">
                <a
                  :title="locale.key"
                  @click.prevent="setEditableData('ru', locale)"
                  @keydown.prevent="setEditableData('ru', locale)"
                >{{
                  locale.valueRu }}</a>
              </td>
              <td class="column-text column-source language">
                <img
                  :class="{ ready: locale.valueRu && locale.valueRu !== '' }"
                  src="/images/flag/ru.svg"
                  alt=""
                  @click.prevent="setEditableData('ru', locale)"
                  @keydown.prevent="setEditableData('ru', locale)"
                >
              </td>
              <td class="column-text column-source language">
                <img
                  :class="{ ready: locale.valueEn && locale.valueEn !== '' }"
                  src="/images/flag/en.svg"
                  alt=""
                  @click.prevent="setEditableData('en', locale)"
                  @keydown.prevent="setEditableData('en', locale)"
                >
              </td>
              <td class="column-text column-source language">
                <img
                  :class="{ ready: locale.valueUz && locale.valueUz !== '' }"
                  src="/images/flag/uz.svg"
                  alt=""
                  @click.prevent="setEditableData('uz', locale)"
                  @keydown.prevent="setEditableData('uz', locale)"
                >
              </td>
            </tr>
          </tbody>
        </table>
        <div v-else class="grid-empty-text">Не найдено</div>
      </div>
    </div>
  </Card>
  <BaseDialog :opened="isOpened as boolean" @close="toggleModal(false)" @submit="handleSubmitEditableLocale()">
    <template #title>Редактирование перевода</template>
    <div class="position-relative">
      <OverlayLoading v-if="isUpdateLocaleDictionary" />
      <div class="col-md-12">
        <div class="field-required">
          <input v-model="editableLocale.key" disabled class="form-control">
        </div>
      </div>
      <BootstrapTabs>
        <template #links>
          <BootstrapTabsLink
            tab-name="locale-ru"
            :is-active="editableLocale.activeLocale === 'ru'"
            :is-required="false"
            :is-disabled="false"
            @click="setActiveTab('ru')"
          >
            <img
              :class="{ ready: editableLocale.valueRu && editableLocale.valueRu !== '' }"
              class="language-image"
              src="/images/flag/ru.svg"
              alt=""
            >
          </BootstrapTabsLink>
          <BootstrapTabsLink
            tab-name="locale-en"
            :is-active="editableLocale.activeLocale === 'en'"
            :is-required="false"
            :is-disabled="false"
            @click="setActiveTab('en')"
          >
            <img
              :class="{ ready: editableLocale.valueEn && editableLocale.valueEn !== '' }"
              class="language-image"
              src="/images/flag/en.svg"
              alt=""
            >
          </BootstrapTabsLink>
          <BootstrapTabsLink
            tab-name="locale-uz"
            :is-active="editableLocale.activeLocale === 'uz'"
            :is-required="false"
            :is-disabled="false"
            @click="setActiveTab('uz')"
          >
            <img
              :class="{ ready: editableLocale.valueUz && editableLocale.valueUz !== '' }"
              class="language-image"
              src="/images/flag/uz.svg"
              alt=""
            >
          </BootstrapTabsLink>
        </template>
        <template #tabs>
          <BootstrapTabsTabContent tab-name="locale-ru" :is-active="editableLocale.activeLocale === 'ru'">
            <form ref="clientGeneralForm" class="tab-content">
              <div class="col-md-12 mt-2">
                <div class="field-required">
                  <textarea v-model="editableLocale.valueRu" placeholder="Русский" class="form-control" />
                </div>
              </div>
            </form>
          </BootstrapTabsTabContent>
          <BootstrapTabsTabContent tab-name="locale-en" :is-active="editableLocale.activeLocale === 'en'">
            <form ref="clientGeneralForm" class="tab-content">
              <div class="col-md-12 mt-2">
                <div class="field-required">
                  <textarea v-model="editableLocale.valueEn" placeholder="Английский" class="form-control" />
                </div>
              </div>
            </form>
          </BootstrapTabsTabContent>
          <BootstrapTabsTabContent tab-name="locale-uz" :is-active="editableLocale.activeLocale === 'uz'">
            <form ref="clientGeneralForm" class="tab-content">
              <div class="col-md-12 mt-2">
                <div class="field-required">
                  <textarea v-model="editableLocale.valueUz" placeholder="Узбекистанский" class="form-control" />
                </div>
              </div>
            </form>
          </BootstrapTabsTabContent>
        </template>
      </BootstrapTabs>
    </div>
    <template #actions-end>
      <button
        class="btn btn-primary"
        type="button"
        :disabled="isUpdateLocaleDictionary"
        @click="handleSubmitEditableLocale()"
      >
        Сохранить
      </button>
      <button
        class="btn btn-cancel"
        type="button"
        :disabled="isUpdateLocaleDictionary"
        @click="toggleModal(false)"
      >
        Отмена
      </button>
    </template>
  </BaseDialog>
</template>

<style lang="scss" scoped>
@use "~resources/sass/vendor/bootstrap/configuration" as bs;

.table-striped {
  margin: 0;
}

.quicksearch-wrapper input {
  width: 100%;
}

.translate {
  vertical-align: middle;

  a {
    color: inherit;
    cursor: pointer;

    &:hover {
      text-decoration: underline;
    }
  }
}

.language {
  vertical-align: middle;
  width: 48px;
  text-align: center;
  cursor: pointer;

  img,
  .language-image {
    vertical-align: middle;
    width: 24px;
    box-shadow: 0 0 2px rgba(0, 0, 0, 20%);
  }

  img.ready {
    opacity: 0.25;

    &:hover {
      opacity: 0.6;
    }
  }
}

.language-image {
  vertical-align: middle;
  width: 24px;
  box-shadow: 0 0 2px rgba(0, 0, 0, 20%);

  &.ready {
    opacity: 0.25;
  }
}

textarea {
  min-height: 6rem;
  padding: 0.75rem;
  line-height: 1.3rem;
}
</style>
