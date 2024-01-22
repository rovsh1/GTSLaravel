<script lang="ts" setup>
import { nextTick, onMounted, ref, watch } from 'vue'

import { onClickOutside } from '@vueuse/core'

import EditableButton from './EditableButton.vue'

const props = withDefaults(defineProps<{
  value: number | null
  enableContextMenu?: boolean
  valuePlaceHolder?: any
}>(), {
  enableContextMenu: false,
  valuePlaceHolder: '',
})

const emit = defineEmits<{
  (event: 'change', value: number | null): void
  (event: 'activatedContextMenu'): void
}>()

const inputRef = ref<HTMLElement | null>(null)
const isChanged = ref(false)
const localValue = ref<number | null>(props.value)

watch(() => props.value, (newValue) => {
  localValue.value = newValue
})

watch(isChanged, (newValue) => {
  if (newValue) {
    nextTick(() => {
      if (inputRef.value instanceof HTMLInputElement) {
        inputRef.value?.select()
      }
    })
  }
})

const transformInputValue = () => {
  if (inputRef.value instanceof HTMLInputElement) {
    const inputValue = inputRef.value?.value
    const numValue = inputValue?.trim() === '' ? NaN : Number(inputValue)
    if (!isNaN(numValue) && numValue >= 1) {
      localValue.value = numValue
    } else {
      localValue.value = null
    }
  } else {
    localValue.value = null
  }
}

const applyEditable = () => {
  if (props.value !== localValue.value) {
    emit('change', localValue.value)
  }
}

const hideEditable = () => {
  applyEditable()
  isChanged.value = false
}

onMounted(() => {
  onClickOutside(inputRef, hideEditable)
})

</script>

<template>
  <div :style="{ height: isChanged ? '100%' : 'auto' }" aria-hidden="true" @click.prevent="isChanged = !isChanged">
    <a v-if="!isChanged">
      <span v-if="value !== null">{{ value }}</span>
      <span v-else class="value-place-holder">{{ valuePlaceHolder }}</span>

      <EditableButton v-if="enableContextMenu" @click.stop="() => { emit('activatedContextMenu') }" />
    </a>
    <input
      v-else
      ref="inputRef"
      :value="value"
      class="form-control"
      @keydown.esc="hideEditable"
      @keydown.enter.stop="hideEditable"
      @click.stop
      @input="transformInputValue"
    />
  </div>
</template>

<style lang="scss" scoped>
input {
  height: 100%;
  border-radius: 0;
  font-size: 0.688rem;
  line-height: 2rem;
  text-align: center;
}

span {
  padding: 0 0.313rem;
}

.value-place-holder {
  color: #000;
  opacity: 0.6;
}
</style>
