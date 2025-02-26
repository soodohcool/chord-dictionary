<template>
  <div class="sequence-container">
    <div class="sequence-display">
      <ChordDisplay v-for="(chord, index) in parsedChords" :key="index" :chord="chord" />
    </div>
  </div>
</template>

<script setup>
import { defineProps } from 'vue'
import ChordDisplay from './ChordDisplay.vue'

// Props
defineProps({
  parsedChords: {
    type: Array,
    required: true,
  },
})
</script>

<style scoped>
.sequence-container {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.sequence-display {
  display: flex;
  flex-wrap: wrap;
  gap: 15px;
  justify-content: center;
  margin-top: 10px;
}
</style>
