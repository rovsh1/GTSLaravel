<script lang="ts" setup>
import { ref } from 'vue'

import BootstrapButton from './BootstrapButton.vue'

import {
  buttonSeverity,
  buttonSize,
  buttonType,
  buttonVariant,
} from './lib'

const buttonTypes = Object.values(buttonType)
const selectedType = ref(buttonTypes[0])

const plusIcon = ref<string>('add')
const startIcon = ref<string>()
const endIcon = ref<string>()
const onlyIcon = ref<string>()
const href = ref(false)
const disabled = ref(false)
const loading = ref(false)

const severities = Object.values(buttonSeverity)

const variants = Object.values(buttonVariant)
const selectedVariant = ref(variants[0])

const sizes = Object.values(buttonSize)
const selectedSize = ref(sizes[0])
</script>
<template>
  <fieldset class="form">
    <div class="form-check">
      <input
        id="href"
        v-model="href"
        type="checkbox"
        class="form-check-input"
      >
      <label class="form-check-label" for="href">HREF</label>
    </div>
    <div class="form-check">
      <input
        id="disabled"
        v-model="disabled"
        type="checkbox"
        class="form-check-input"
      >
      <label class="form-check-label" for="disabled">Disabled</label>
    </div>
    <div class="form-check">
      <input
        id="loading"
        v-model="loading"
        type="checkbox"
        class="form-check-input"
      >
      <label class="form-check-label" for="loading">Loading</label>
    </div>
    <div class="form-check">
      <input
        id="start-icon"
        v-model="startIcon"
        type="checkbox"
        class="form-check-input"
      >
      <label class="form-check-label" for="start-icon">Start icon</label>
    </div>
    <div class="form-check">
      <input
        id="end-icon"
        v-model="endIcon"
        type="checkbox"
        class="form-check-input"
      >
      <label class="form-check-label" for="end-icon">End icon</label>
    </div>
    <div class="form-check">
      <input
        id="only-icon"
        v-model="onlyIcon"
        type="checkbox"
        class="form-check-input"
      >
      <label class="form-check-label" for="only-icon">Only icon</label>
    </div>
  </fieldset>
  <fieldset class="form">
    <div>
      <div class="form-label">Variant</div>
      <div class="radio">
        <div
          v-for="variant in variants"
          :key="variant"
          class="form-check"
        >
          <input
            :id="`variant-${variant}`"
            :value="variant"
            :checked="selectedVariant === variant"
            class="form-check-input"
            name="variant"
            type="radio"
            @input="() => selectedVariant = variant"
          >
          <label class="form-check-label" :for="`variant-${variant}`">{{ variant }}</label>
        </div>
      </div>
    </div>
    <div>
      <div class="form-label">Size</div>
      <div class="radio">
        <div
          v-for="size in sizes"
          :key="size"
          class="form-check"
        >
          <input
            :id="`size-${size}`"
            :value="size"
            :checked="selectedSize === size"
            class="form-check-input"
            name="size"
            type="radio"
            @input="() => selectedSize = size"
          >
          <label class="form-check-label" :for="`size-${size}`">{{ size }}</label>
        </div>
      </div>
    </div>
    <div>
      <div class="form-label">Type</div>
      <div class="radio">
        <div
          v-for="type in buttonTypes"
          :key="type"
          class="form-check"
        >
          <input
            :id="`type-${type}`"
            :value="type"
            :checked="selectedType === type"
            class="form-check-input"
            name="type"
            type="radio"
            @input="() => selectedType = type"
          >
          <label class="form-check-label" :for="`type-${type}`">{{ type }}</label>
        </div>
      </div>
    </div>
  </fieldset>
  <div class="demos">
    <div class="demo">
      <BootstrapButton
        v-for="severity in severities"
        :key="severity"
        class="button"
        :type="selectedType"
        :severity="severity"
        :label="severity"
        :href="href ? 'https://google.com' : undefined"
        :start-icon="startIcon ? plusIcon : undefined"
        :end-icon="endIcon ? plusIcon : undefined"
        :only-icon="onlyIcon ? plusIcon : undefined"
        :disabled="disabled"
        :loading="loading"
        :variant="selectedVariant"
        :size="selectedSize"
      />
    </div>
  </div>
</template>
<style lang="scss" scoped>
.form {
  display: flex;
  flex-wrap: wrap;
  gap: 2em;
  align-items: center;
  padding: 1em;
}

.radio {
  display: flex;
  gap: 1em;
  align-items: center;
}

.demos {
  display: flex;
  flex-flow: column;
  gap: 2em;
  padding: 1em;
}

.demo {
  display: flex;
  flex-wrap: wrap;
  gap: 1em;
  align-items: center;
}

.button {
  text-transform: capitalize;
}
</style>
