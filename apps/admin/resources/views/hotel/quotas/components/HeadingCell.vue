<script lang="ts" setup>
defineProps<{
  label: string
  customName: string
  guests: number
  count: number
}>()
</script>
<template>
  <div><strong>{{ label }}</strong> ({{ customName }})</div>
  <dl class="roomTypeStats">
    <dt class="roomTypeStatLabel">Количество гостей:</dt>
    <dd class="roomTypeStatValue">{{ guests }}</dd>
    <dt class="roomTypeStatLabel">Количество номеров:</dt>
    <dd class="roomTypeStatValue">{{ count }}</dd>
  </dl>
</template>
<style lang="scss" scoped>
@use '~resources/sass/variables' as vars;

.roomTypeStats {
  display: grid;
  grid-template-columns: repeat(2, min-content);
  gap: 0 0.25em;
  margin: unset;
}

.roomTypeStatLabel {
  color: gray;
}

.roomTypeStatValue {
  margin: unset;
  color: vars.$error;
  font-weight: bold;
}
</style>
