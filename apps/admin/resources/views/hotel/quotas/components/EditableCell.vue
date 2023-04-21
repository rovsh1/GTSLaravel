<script lang="ts" setup>
import { ref, watch } from 'vue'

type CellKey = string | null

defineProps<{
  cellKey: string
  activeKey: CellKey
  value: string
}>()

const emit = defineEmits<{
  (event: 'active-key', value: CellKey): void
}>()

const inputRef = ref<HTMLInputElement | null>(null)

const handleInput = (input: HTMLInputElement) => {
  input.focus()
  input.select()
}

watch(inputRef, (element) => {
  if (element === null) return
  handleInput(element)
})
</script>
<template>
  <input
    v-if="activeKey === cellKey"
    :ref="(element) => inputRef = element as HTMLInputElement"
    :value="value"
    class="editableCellInput"
    @blur="() => emit('active-key', null)"
  >
  <button
    v-else
    type="button"
    class="editableDataCell"
    @click="() => emit('active-key', cellKey)"
  >
    <slot />
  </button>
</template>
<style lang="scss" scoped>
@use './shared' as shared;

%cell {
  @include shared.cell;
}

%data-cell {
  @include shared.data-cell;
}

.editableCellInput {
  @extend %data-cell;

  width: 100%;
  font-size: inherit;
}

.editableDataCell {
  @extend %data-cell;

  width: 100%;
  border: 2px solid transparent;
  background-color: unset;

  &:hover {
    background-color: rgba(black, 0.1);
  }
}
</style>
