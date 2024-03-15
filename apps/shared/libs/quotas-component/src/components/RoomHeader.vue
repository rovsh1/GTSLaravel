<script lang="ts" setup>
import { pluralForm } from 'gts-common/helpers/plural'

defineProps<{
  label: string
  guests: number
  count: number
}>()
</script>
<template>
  <div>
    <strong>{{ label }}</strong>
  </div>
  <p>
    <span class="roomTypeStatValue">{{ guests }}</span>
    <span class="roomTypeStatLabel">{{ pluralForm(guests, ['гость', 'гостя', 'гостей']) }}</span>,
    <span class="roomTypeStatValue">{{ count }}</span>
    <span class="roomTypeStatLabel">{{ pluralForm(count, ['номер', 'номера', 'номеров']) }}</span>
  </p>
</template>
<style lang="scss" scoped>
@use '~assets/sass/configuration' as bs;

.roomTypeStats {
  display: grid;
  grid-template-columns: repeat(2, max-content);
  gap: 0 0.25em;
  margin: unset;
}

.roomTypeStatLabel {
  color: gray;
}

.roomTypeStatValue {
  margin: unset;
  margin-right: 0.25em;
  color: bs.$error;
  font-weight: bold;
}
</style>
