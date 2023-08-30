<script lang="ts" setup>
import { nextTick, onMounted, ref, watch } from 'vue'

import { onClickOutside } from '@vueuse/core'

import EditableButton from './EditableButton.vue'

const props = withDefaults(defineProps<{
  value: number | null
  enableContextMenu?: boolean
}>(), {
  enableContextMenu: false,
})

// todo формат цен, слить ветку GTS-1980

const emit = defineEmits<{
  (event: 'change', value: number | null): void
  (event: 'activatedContextMenu'): void
}>()

const inputRef = ref<HTMLElement | null>(null)
const isChanged = ref(false)
const localValue = ref<number | null>(props.value)

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
    if (!isNaN(numValue)) {
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
      <span>{{ value }}</span>

      <EditableButton v-if="enableContextMenu" @click.stop="() => { emit('activatedContextMenu') }" />
    </a>
    <input
      v-else
      ref="inputRef"
      :value="value"
      class="form-control"
      @keydown.esc="hideEditable"
      @keydown.enter="hideEditable"
      @click.stop
      @input="transformInputValue"
    />
  </div>
</template>

<style lang="scss" scoped>
input {
  height: 100%;
  border-radius: 0;
  font-size: 11px;
  line-height: 32px;
  text-align: center;
}

span {
  padding: 0 5px;
}
</style>
