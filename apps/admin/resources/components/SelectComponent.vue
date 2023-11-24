<script lang="ts" setup>
import { computed, onMounted, ref, watch } from 'vue'

import $ from 'jquery'
import { nanoid } from 'nanoid'

import { useSelectElement } from '~lib/select-element/select-element'

import { SelectOption } from '~components/Bootstrap/lib'

type LabelStyle = 'default' | 'outline'

type SelectValue = Array<string | number> | string | number | null | undefined

const props = withDefaults(defineProps<{
  value: SelectValue
  options: SelectOption[]
  name?: string
  label?: string
  labelStyle?: LabelStyle
  required?: boolean
  multiple?: boolean
  disabled?: boolean
  disabledPlaceholder?: string
  placeholder?: string
  allowEmptyItem?: boolean
  emptyItem?: string
  emptyText?: string
  enableTags?: boolean

}>(), {
  name: undefined,
  label: '',
  labelStyle: 'default',
  disabled: false,
  required: false,
  multiple: false,
  disabledPlaceholder: undefined,
  placeholder: undefined,
  allowEmptyItem: false,
  emptyItem: '',
  emptyText: 'Пусто',
  enableTags: false,
})

const emit = defineEmits<{
  (event: 'change', value: any): void
}>()

const id = `select-element-${nanoid()}`
const componentInstance = ref()

const groupOptions = computed(() => {
  const allOptions: SelectOption[] = props.options
  const groupedData: any = {}
  allOptions.forEach((item) => {
    const group = item.group ? item.group : ''
    if (!groupedData[group]) {
      groupedData[group] = []
    }
    groupedData[group].push(item)
  })
  return groupedData
})

const convertValueToSelectType = (value: SelectValue): string | string[] => {
  if (value !== null && value !== undefined) {
    if (props.multiple) {
      return Array.isArray(value) ? value.map((item) => (item ? item.toString().trim() : '')) : [value.toString().trim()]
    }
    return Array.isArray(value) ? value[0]?.toString().trim() : value.toString().trim()
  }
  return []
}

const isSameValues = (oldValue: SelectValue, newValue: SelectValue): boolean => {
  if (Array.isArray(oldValue) && Array.isArray(newValue)) {
    return oldValue.length === newValue.length && oldValue.every((value, index) =>
      value.toString().trim() === newValue[index].toString().trim())
  } if (oldValue === newValue) {
    return true
  }
  return false
}

const setValue = (value: SelectValue) => {
  const val = convertValueToSelectType(value)
  componentInstance.value?.select2Instance.val(val).trigger('change')
}

const initSelectElement = async () => {
  const element = document.querySelector<HTMLSelectElement>(`#${id}`)
  const instance = await useSelectElement(element, {
    multiple: props.multiple,
    disabled: props.disabled,
    disabledPlaceholder: props.disabledPlaceholder,
    placeholder: props.placeholder,
    emptyText: props.emptyText,
  })
  componentInstance.value = instance
  setValue(props.value)
  componentInstance.value?.select2Instance.on('change', (e: any) => {
    const selectedValues = $(e.target).val()
    emit('change', selectedValues)
  })
}

onMounted(async () => {
  await initSelectElement()
})

watch(
  [() => props.options, () => props.disabled, () => props.disabledPlaceholder],
  async () => {
    await initSelectElement()
  },
)

watch(
  () => props.value,
  (newValue) => {
    if (!isSameValues(props.value, newValue)) {
      setValue(newValue)
    }
  },
)

</script>

<template>
  <div class="select-element" :class="{ 'field-required': required }">
    <label :for="id" :class="[labelStyle === 'outline' ? 'label a1' : 'form-label']">{{ label }}</label>
    <div style="height: 35px">
      <select
        :id="id"
        class="form-select form-control select-height-control"
        :name="name"
        :disabled="disabled"
        :required="required"
        :multiple="multiple"
      >
        <template v-if="!enableTags">
          <option v-if="allowEmptyItem" value="">{{ emptyItem }}</option>
          <option v-for="option in options" :key="option.value" :value="option.value">
            {{ option.label }}
          </option>
        </template>
        <template v-else>
          <option v-if="allowEmptyItem" value="">{{ emptyItem }}</option>
          <optgroup v-for="(optionInGroup, nameGroup) in groupOptions" :key="nameGroup" :label="nameGroup.toString()">
            <option v-for="option in optionInGroup" :key="option.value" :value="option.value">
              {{ option.label }}
            </option>
          </optgroup>
        </template>
      </select>
    </div>
  </div>
</template>

<style lang="scss" scoped>
@use '~resources/sass/vendor/bootstrap/configuration' as bs;

.select-element {
  position: relative;
}

.select-height-control,
.select2  {
  min-height: 34px;
}

.label {
  --padding-block: 0.1em;
  --padding-inline: 0.5em;
  --left: calc(#{bs.$form-select-padding-x} - var(--padding-inline) + 0.1em);

  position: absolute;
  top: calc(-0.9em - var(--padding-block));
  left: var(--left);
  z-index: 1;
  overflow: hidden;
  max-width: calc(100% - var(--left));
  padding: var(--padding-block) var(--padding-inline);
  border-radius: 0.5em;
  background-color: bs.$body-bg;
  font-size: 0.8em;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.field-required {
  width: 100%;
}
</style>
