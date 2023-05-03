<script lang="ts" setup>
import { ref } from 'vue'

import crossIcon from '@mdi/svg/svg/close.svg'
import { MaybeRef, useDropZone } from '@vueuse/core'

import BootstrapButton from '~resources/components/Bootstrap/BootstrapButton/BootstrapButton.vue'

import { SelectedFile } from './lib'

const props = withDefaults(defineProps<{
  value: SelectedFile[] | null
  disabled?: MaybeRef<boolean>
}>(), {
  disabled: false,
})

const emit = defineEmits<{
  (event: 'input', files: File[] | null): void
}>()

const dropZoneRef = ref<HTMLElement>()

const mapDroppedFilesToRaw = ({ raw }: SelectedFile): File => raw

const { isOverDropZone } = useDropZone(dropZoneRef, (files) => {
  if (props.disabled) return
  if (props.value === null) {
    emit('input', files)
  } else if (files !== null) {
    const oldFiles = props.value.map(mapDroppedFilesToRaw)
    emit('input', [...oldFiles, ...files])
  }
})

const remove = (idToRemove: string) => {
  if (props.value === null) return
  const newFiles = props.value
    .filter(({ id }) => id !== idToRemove)
    .map(mapDroppedFilesToRaw)
  if (newFiles.length > 0) {
    emit('input', newFiles)
  } else {
    emit('input', null)
  }
}
</script>
<template>
  <div
    ref="dropZoneRef"
    class="dropzone"
    :class="{ isOver: !disabled && isOverDropZone }"
  >
    <div class="dropped">
      <div v-if="value === null">
        Перетащите фото сюда
      </div>
      <div v-else class="items">
        <div
          v-for="{ name, size, src, id } in value"
          :key="id"
        >
          <div class="image">
            <BootstrapButton
              label="Убрать"
              :only-icon="crossIcon"
              severity="danger"
              variant="filled"
              size="small"
              class="removeButton"
              :disabled="disabled"
              @click="() => remove(id)"
            />
            <img :src="src" :alt="name" class="picture">
            <div class="size">{{ size }}</div>
          </div>
          <div class="name">{{ name }}</div>
        </div>
      </div>
    </div>
  </div>
</template>
<style lang="scss" scoped>
.dropzone {
  display: flex;
  flex-flow: column;
  gap: 1em;
  align-items: center;
  min-height: 15em;
  padding: 1em;
  border: 2px dashed lightgray;
  border-radius: 0.75em;

  &.isOver {
    border-color: blue;
  }
}

.dropped {
  display: flex;
  flex-grow: 1;
  justify-content: center;
  align-items: center;
  width: 100%;
}

.items {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1em;
}

.image {
  position: relative;
  display: flex;
  flex-flow: column;
  gap: 0.5em;
}

.removeButton {
  position: absolute;
  top: 0;
  right: 0;
  margin: 0.5em;
}

.size {
  position: absolute;
  bottom: 0;
  left: 0;
  margin: 0.5em;
  padding-inline: 0.25em;
  border-radius: 0.2em;
  background-color: rgba(black, 0.9);
  color: white;
  font-size: 0.8em;
}

.picture {
  object-fit: cover;
  object-position: center;
  max-width: 100%;
  border-radius: 0.75em;
  aspect-ratio: 1/1;
}

.name {
  overflow: hidden;
  max-width: 100%;
  font-size: 0.9em;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.input {
  display: flex;
  flex-shrink: 0;
  flex-flow: column;
  align-items: center;
}
</style>
