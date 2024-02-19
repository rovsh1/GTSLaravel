<script lang="ts" setup>
import { computed, nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue'

import { useSelectElement } from 'gts-common/select-element'
import $ from 'jquery'
import { nanoid } from 'nanoid'

import { SelectOption } from '~components/Bootstrap/lib'

type LabelStyle = 'default' | 'outline'

type SelectValue = Array<string | number> | string | number | undefined | null

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
  returnedEmptyValue?: any
  minimize?: boolean

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
  returnedEmptyValue: undefined,
  minimize: false,
})

const emit = defineEmits<{
  (event: 'change', value: any, element: any): void
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
  } if (oldValue?.toString() === newValue?.toString()) {
    return true
  }
  return false
}

const setValue = (value: SelectValue) => {
  const val = convertValueToSelectType(value)
  if (props.allowEmptyItem && !!props.emptyItem
    && !props.multiple && !value) {
    componentInstance.value?.select2Instance.val(null).trigger('change')
  } else {
    componentInstance.value?.select2Instance.val(val).trigger('change')
  }
}

const initSelectElement = async () => {
  const element = document.querySelector<HTMLSelectElement>(`#${id}`)
  const instance = await useSelectElement(element, {
    multiple: props.multiple,
    disabled: props.disabled,
    disabledPlaceholder: props.disabledPlaceholder,
    placeholder: props.placeholder,
    emptyText: props.emptyText,
    minimize: props.minimize,
  })
  componentInstance.value = instance
  setValue(props.value)
  componentInstance.value?.select2Instance.on(`select2:select ${props.multiple ? 'select2:unselect' : ''}`, (e: any) => {
    const selectedValues = $(e.target).val()
    if (!isSameValues(props.value, selectedValues)) {
      emit('change', selectedValues || props.returnedEmptyValue, e.target)
    }
  })
}

onMounted(async () => {
  await nextTick(async () => {
    await initSelectElement()
  })
})

onBeforeUnmount(() => {
  componentInstance.value?.select2Instance?.select2('destroy')
})

watch(
  [() => props.options, () => props.disabled, () => props.disabledPlaceholder],
  () => {
    nextTick(async () => {
      await initSelectElement()
    })
  },
)

watch(
  () => props.value,
  (newValue, oldValue) => {
    if (!isSameValues(oldValue, newValue)) {
      setValue(newValue)
    }
  },
)

</script>

<template>
  <div class="select-element" :class="{ 'field-required': required && label !== '' }">
    <label v-if="label !== ''" :for="id" :class="[labelStyle === 'outline' ? 'label a1' : 'form-label']">{{ label
    }}</label>
    <div
      :class="[labelStyle === 'outline' ? 'outline-style' : '',
               minimize ? 'select-element-minimize' : 'select-element-normal']"
    >
      <select
        :id="id"
        class="form-select form-control select-height-control"
        :name="name"
        :disabled="disabled"
        :required="required"
        :multiple="multiple"
      >
        <template v-if="!enableTags">
          <option v-if="allowEmptyItem && !!emptyItem" value="">{{ emptyItem }}</option>
          <option v-else-if="allowEmptyItem" value="">{{ emptyItem }}</option>
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

<style lang="scss">
@use '~resources/sass/vendor/bootstrap/configuration' as bs;

.select-element {
  position: relative;
  width: 100%;
}

.select-element .select-height-control,
.select-element .select2 {
  min-height: 2.125rem;
}

.select-element .label {
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

.select-element .outline-style .select2-selection {
  border-radius: 0.375rem;
}

.select-element .field-required {
  width: 100%;
}

.select-element-minimize {
  height: auto;
}

.select-element-normal {
  height: 2.188rem;
}
</style>
