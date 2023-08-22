<script lang="ts" setup>
import { nextTick, onMounted, ref, watch } from 'vue'

import RoomEditableButton from './RoomEditableButton.vue'

const props = withDefaults(defineProps<{
  isEdit: boolean
}>(), {

})

const inputElement = ref<HTMLElement | null>(null)

const open = ref(false)

watch(() => props.isEdit, (newValue) => {
  if (newValue) {
    nextTick(() => {
      inputElement.value?.focus()
    })
  }
})

onMounted(() => {
  /* document.addEventListener('click', (event) => {
    if (event.target !== inputElement.value) {
      open.value = true
    }
  }) */
})
</script>

<template>
  <a v-if="!isEdit" @click.prevent="open = !open" @keydown.prevent="open = !open">
    <span>10 000</span>

    <RoomEditableButton @click.stop="() => { return false }" />
  </a>
  <input v-else ref="inputElement" class="form-control" @input.stop="() => { return false }" />
</template>

<style lang="scss" scoped>
input {
  height: 100%;
  margin-bottom: -1px;
  border-radius: 0;
  font-size: 11px;
  line-height: 32px;
  text-align: center;
}
</style>
