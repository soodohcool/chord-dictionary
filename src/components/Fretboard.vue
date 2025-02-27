<template>
  <div class="fretboard-container">
    <div class="fretboard">
      <!-- Strings (vertical lines) -->
      <div v-for="stringIndex in 6" :key="'string-' + stringIndex" :class="'string string-' + stringIndex"
        :style="{ left: `${(stringIndex - 1) * 20}%` }"></div>

      <!-- Frets (horizontal lines) -->
      <div v-for="fretNum in 5" :key="'fret-' + fretNum" class="fret" :style="{ top: `${fretNum * 20}%` }"></div>

      <!-- Finger positions rendered directly -->
      <template v-if="fingerPositions.length">
        <div v-for="(fingerData, idx) in fingerPositions" :key="'finger-' + idx"
          :class="['finger', getFingerClass(fingerData.finger)]" :style="{
            left: `${(6 - fingerData.string) * 20}%`,
            top: `${((fingerData.fret - 1) * 20) + 10}%`,
          }">
          {{ fingerData.finger }}
        </div>
      </template>

      <!-- String labels at the bottom -->
      <div v-for="(label, index) in stringLabels" :key="'label-' + index" class="string-label"
        :style="{ left: `${index * 20}%` }">
        {{ label }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'

// Props
const props = defineProps({
  chord: {
    type: String,
    required: true,
  },
  chordData: {
    type: Object,
    required: true,
  },
})

// String labels (E A D G B E from left to right)
const stringLabels = ref(['E', 'A', 'D', 'G', 'B', 'E'])

// Compute valid finger positions
const fingerPositions = computed(() => {
  if (!props.chordData || !props.chordData.fingers) {
    return []
  }
  return props.chordData.fingers.filter((f) => f && typeof f.fret === 'number' && f.fret > 0)
})

// Get finger class based on finger number
function getFingerClass(finger) {
  switch (parseInt(finger)) {
    case 1:
      return 'finger-1' // Index finger - Purple
    case 2:
      return 'finger-2' // Middle finger - Orange
    case 3:
      return 'finger-3' // Ring finger - Green
    case 4:
      return 'finger-4' // Pinky finger - Pink
    default:
      return ''
  }
}
</script>

<style>
.fretboard-container {
  width: 100%;
  margin: 0;
  padding: 0;
  position: relative;
}

.fretboard {
  position: relative;
  width: 100%;
  height: 120px;
  background-color: #f9f9f9;
  border: 1px solid #ddd;
  border-top: 6px solid #000;
  border-radius: 0px;
  margin: 10px 0 0 0;
  top: 0;
  padding-bottom: 20px;
  /* Add space for the labels */
}

.string {
  position: absolute;
  top: 0px;
  height: 100%;
  background-color: #777;
  transform: translateX(-50%);
  /* Center the line */
}

.string.string-1 {
  width: 6px;
  left: calc(0% + 2px) !important;
}

.string.string-2 {
  width: 5px;
}

.string.string-3 {
  width: 4px;
}

.string.string-4 {
  width: 3px;
}

.string.string-5 {
  width: 2px;
}

.fret {
  position: absolute;
  left: 0;
  width: 100%;
  height: 1px;
  background-color: #777;
  transform: translateY(-50%);
  /* Center the line */
}

.finger {
  position: absolute;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
  font-weight: bold;
  transform: translate(-50%, -50%);
  /* Center the dot on the intersection */
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
  z-index: 10;
  background-color: #333;
  /* Default color */
}

.finger-1 {
  background-color: #9c27b0 !important;
  /* Purple for index finger */
}

.finger-2 {
  background-color: #ff9800 !important;
  /* Orange for middle finger */
}

.finger-3 {
  background-color: #4caf50 !important;
  /* Green for ring finger */
}

.finger-4 {
  background-color: #ff4081 !important;
  /* Bright pink for pinky finger */
}

.string-label {
  position: absolute;
  bottom: -20px;
  /* Position below the fretboard */
  transform: translateX(-50%);
  font-size: 12px;
  color: #999;
  /* Same color as strings */
  font-weight: normal;
}
</style>
