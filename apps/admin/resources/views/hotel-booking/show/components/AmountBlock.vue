<script setup lang="ts">
import { Currency } from '~api/models'
import { priceFormatter } from '~lib/price-formatter'

withDefaults(defineProps<{
  title: string
  currency: Currency | undefined
  amountTitle: string
  amountValue: number
  penaltyTitle: string
  penaltyValue: number | null | undefined
  needShowPenalty?: boolean
}>(), {
  penaltyValue: undefined,
  needShowPenalty: false,
})

defineEmits<{
  (event: 'clickChangePrice'): void
  (event: 'clickChangePenalty'): void
}>()

</script>

<template>
  <div class="w-50 rounded shadow-lg p-3">
    <h6>{{ title }}</h6>
    <hr>
    <div v-if="currency">
      {{ amountTitle }}: {{ priceFormatter(amountValue, currency.sign) }}
      <!--{{ amountTitle }}: {{ amountValue }}
      <span class="currency">{{ currency.sign }}</span>-->
    </div>
    <a href="#" @click.prevent="$emit('clickChangePrice')">Изменить</a>

    <div v-if="needShowPenalty && currency">
      <div>
        {{ penaltyTitle }}: {{ priceFormatter((penaltyValue || 0), currency.sign) }}
        <!--{{ penaltyTitle }}: {{ penaltyValue || 0 }}
        <span class="currency">{{ currency.sign }}</span>-->
      </div>
      <a href="#" @click.prevent="$emit('clickChangePenalty')">Изменить</a>
    </div>
  </div>
</template>

<style scoped lang="scss">
hr {
  margin: 0.5rem 0 0.75rem;
}
</style>
