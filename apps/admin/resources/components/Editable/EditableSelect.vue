<script setup lang="ts">

import { computed, ref } from 'vue'

import { onClickOutside, useToggle } from '@vueuse/core'

import { SelectedValue, SelectOption } from '~components/Bootstrap/lib'
import Select2BaseSelect from '~components/Select2BaseSelect.vue'

const props = withDefaults(defineProps<{
  id: string
  value: SelectedValue
  items: SelectOption[]
  emptyValue?: string
  required?: boolean
  canEdit?: boolean
  showEmptyItem?: boolean
}>(), {
  placeholder: undefined,
  emptyValue: undefined,
  required: false,
  canEdit: true,
  showEmptyItem: true,
})

const emit = defineEmits<{
  (event: 'change', value: SelectedValue): void
}>()

const [isEditable, toggleEditable] = useToggle()
const toggleEditMode = (value?: boolean) => {
  toggleEditable(value)
  if (value) {
    setTimeout(() => $(`#${props.id}`).select2('open'))
  }
}

const localValue = computed(() => props.value)

const displayValue = computed(() => {
  if (!localValue.value) {
    return props.emptyValue || 'Не установлена'
  }

  return (props.items.find(({ value }) => localValue.value === value))?.label
})

const selectRef = ref<HTMLSelectElement | null>(null)
const hideEditable = () => {
  toggleEditMode(false)
}

const applyEditable = (value: SelectedValue) => {
  const isEmptyValue = String(value).trim().length === 0
  if (props.required && isEmptyValue) {
    return
  }
  emit('change', value)
  toggleEditMode(false)
}

onClickOutside(selectRef, hideEditable)

const onPressEsc = () => {
  hideEditable()
}

</script>

<template>
  <div>
    <a v-if="!isEditable && canEdit" href="#" @click.prevent="toggleEditMode(true)">
      {{ displayValue }}
    </a>

    <span v-else-if="!canEdit">
      {{ displayValue }}
    </span>

    <Select2BaseSelect
      v-else
      :id="id"
      ref="selectRef"
      :options="items"
      :value="value"
      :required="required"
      :show-empty-item="showEmptyItem"
      @keyup.esc="onPressEsc"
      @input="applyEditable"
    />
  </div>
</template>

<style lang="scss" scoped>
svg {
  width: auto;
  height: 1em;
  fill: currentcolor;
}
</style>
