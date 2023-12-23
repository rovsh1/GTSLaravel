<script lang="ts" setup>

import InlineIcon from '~components/InlineIcon.vue'

defineProps<{
  visible: boolean
}>()

const emit = defineEmits<{
  (event: 'click', value: HTMLElement): void
}>()

const handleClick = (target: EventTarget | null) => {
  if (target === null) return
  emit('click', target as HTMLElement)
}
</script>
<template>
  <button
    type="button"
    class="menuButton"
    :class="{ visible }"
    @click="(event) => handleClick(event.currentTarget)"
  >
    <InlineIcon icon="menu" class="menuButtonIcon" />
  </button>
</template>
<style lang="scss" scoped>
.menuButton {
  display: flex;
  place-content: center;
  width: 100%;
  padding-block: 0.25em;
  border: unset;
  background: unset;

  &.visible,
  &:hover {
    %hovered-icon {
      visibility: visible;
    }
  }

  &:hover {
    background-color: rgba(black, 0.1);
  }
}

.menuButtonIcon {
  @extend %hovered-icon;

  display: block;
  height: 1.2em;
  visibility: hidden;
  aspect-ratio: 1/1;
}
</style>
