<template>
  <div class="open-strings">
    <div v-for="stringNum in 6" :key="'string-' + stringNum" class="open-string"
      :class="{ muted: isStringMuted(chord, stringNum) }" :style="{ left: `${(6 - stringNum) * 20}%` }">
      <template v-if="isStringMuted(chord, stringNum)">✕</template>
      <template v-else-if="isOpenString(chord, stringNum)">○</template>
      <template v-else>&nbsp;</template>
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue'
import { isStringMuted, isOpenString } from '../chordDictionary'

// Props
defineProps({
  chord: {
    type: String,
    required: true,
  },
})
</script>

<style scoped>
.open-strings {
  position: relative;
  width: 100%;
  height: 24px;
  margin: 0;
  padding: 0;
}

.open-string {
  position: absolute;
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 16px;
  transform: translateX(-50%);
}

.open-string.muted {
  color: red;
  font-weight: bold;
}
</style>
