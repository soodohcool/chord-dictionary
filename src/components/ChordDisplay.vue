<template>
  <div class="chord-container">
    <div class="chord-name">{{ chord }}</div>
    <div v-if="!chordInfo" class="error-message">Chord not found</div>
    <div v-else class="chord-diagram">
      <OpenStrings :chord="chord" />
      <Fretboard v-if="chordInfo.fingers && chordInfo.fingers.length" :chord="chord" :chord-data="chordInfo" />
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import OpenStrings from './OpenStrings.vue'
import Fretboard from './Fretboard.vue'
import { getChordData } from '../chordDictionary'

// Props
const props = defineProps({
  chord: {
    type: String,
    required: true,
  },
})

// Computed
const chordInfo = computed(() => getChordData(props.chord))
</script>

<style scoped>
.chord-container {
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 15px 25px 30px;
  text-align: center;
  width: 250px;
  background-color: white;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.chord-name {
  font-weight: bold;
  margin-bottom: 10px;
  font-size: 24px;
}

.error-message {
  color: red;
  font-size: 0.9em;
}

.chord-diagram {
  position: relative;
  width: 100%;
  display: flex;
  flex-direction: column;
  gap: 0;
}
</style>
